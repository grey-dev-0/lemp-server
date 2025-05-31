<?php

namespace App\Jobs;

use App\Models\Domain;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProvisionDomain implements ShouldQueue{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param Domain $domain The domain to be provisioned.
     */
    public function __construct(private Domain $domain, private ?string $config = null){}

    /**
     * Generates nginx configuration file for the provisioned domain and, adds it to the web server hosts directory.
     *
     * @return bool Determines whether a new configuration file has been generated or an existing one is kept intact.
     * @throws \Throwable
     */
    private function setServerConfig(){
        \Log::info("Setting server configuration file");
        if(trim(`docker exec -w /etc/nginx/vhosts lemp-nginx-1 bash -c 'grep -u {$this->domain->name} * | wc -l'`) > 0)
            \Log::warning("{$this->domain->name} has been already configured, please edit the config file if changes are required.");
        else{
            $nginxFile = storage_path("app/{$this->domain->name}.conf");
            $nginxConfig = empty($this->config)? view('stubs.nginx', [
                'serverName' => $this->domain->name,
                'type' => (int)$this->domain->project->type,
                'docRoot' => $this->domain->project->path,
                'tls' => (boolean)$this->domain->https
            ])->render() : $this->config;
            file_put_contents($nginxFile, $nginxConfig);
            \Log::info(`docker cp $nginxFile lemp-nginx-1:/etc/nginx/vhosts/`);
            \Log::info("Server configuration file $nginxFile has been updated.");
        }
        return true;
    }

    /**
     * Sets up the https support for the provisioned domain - if requested.
     *
     * @return bool
     */
    private function setupSslSupport(){
        \Log::info("Setting up SSL support for {$this->domain->name}..");
        \Log::info(`mkcert {$this->domain->name} && docker cp {$this->domain->name}.pem lemp-nginx-1:/etc/nginx/ssl/ && docker cp {$this->domain->name}-key.pem lemp-nginx-1:/etc/nginx/ssl/ && rm -f {$this->domain->name}*.pem`);
        \Log::info("Local SSL certificates have been generated and copied for {$this->domain->name}.");
        return true;
    }

    /**
     * Adds the provisioned domain to local hosts files to enable its access from browser - if doesn't exist.
     *
     * @return bool
     */
    private function setupHostsFile(){
        \Log::info("Adding {$this->domain->name} to local hosts file..");
        add_host($this->domain->name);
        \Log::info('Local hosts file has been updated.');
        return true;
    }

    /**
     * Execute the job.
     *
     * @throws \Throwable
     */
    public function handle(): void{
        $this->setServerConfig() && $this->domain->https && $this->setupSslSupport() && $this->setupHostsFile()
            && $this->domain->update(['provisioned_at' => now()]) && \Log::info(`docker exec lemp-nginx-1 nginx -s reload`);
    }
}
