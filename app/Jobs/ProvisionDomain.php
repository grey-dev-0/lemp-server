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
    public function __construct(private Domain $domain){}

    /**
     * Execute the job.
     */
    public function handle(): void{
        $nginxFile = storage_path("app/{$this->domain->name}.conf");
        $nginxConfig = view('stubs.nginx', [
            'serverName' => $this->domain->name,
            'type' => (int)$this->domain->type,
            'docRoot' => $this->domain->project->path,
            'tls' => (boolean)$this->domain->https
        ])->render();
        file_put_contents($nginxFile, $nginxConfig);
        $provision = `docker cp $nginxFile lemp-nginx-1:/etc/nginx/vhosts/ && rm -f $nginxFile`;
        if($this->domain->https)
            $provision .= `mkcert {$this->domain->name} && docker cp {$this->domain->name}.pem lemp-nginx-1:/etc/nginx/ssl/ && docker cp {$this->domain->name}-key.pem lemp-nginx-1:/etc/nginx/ssl/ && rm -f {$this->domain->name}*.pem`;
        $provision .= `docker exec lemp-nginx-1 nginx -s reload`;
        \Log::info("Provisioning domain {$this->domain->name}\n$provision");
        $this->domain->update(['provisioned_at' => now()]);
    }
}
