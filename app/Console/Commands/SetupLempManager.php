<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupLempManager extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lemp:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets up the LEMP manager web app database and server configurations.';

    /**
     * Adds lemp.docker domain to host system's hosts file to enable local access to the LEMP manager app.
     *
     * @return void
     */
    private function setupHosts(){
        $serverIp = trim(`docker inspect lemp-nginx-1 | grep IPAddr | grep --color=none -oE '[0-9]+\.[0-9]+[^"]+'`);
        $hostsFile = fopen('/etc/host.hosts', 'r');
        $ips = [];
        $done = false;
        while(($ip = fgets($hostsFile)) !== false){
            $ip = trim($ip);
            if(str_contains($ip, 'lemp.docker'))
                return;
            if(!str_contains($ip, $serverIp)){
                $ips[] = $ip;
                continue;
            }
            $hosts = preg_split('# +#u', $ip);
            $ip = array_shift($hosts);
            $hosts[] = 'lemp.docker';
            $ips[] = implode(' ', array_merge($ip, $hosts));
            $done = true;
        }
        fclose($hostsFile);
        if(!$done)
            $ips[] = "$serverIp lemp.docker";
        file_put_contents('/etc/host.hosts', implode(PHP_EOL, $ips));
    }

    /**
     * Execute the console command.
     */
    public function handle(){
        $this->info('Setting up server configurations..');
        $output = `mkcert lemp.docker && docker cp lemp.docker.pem lemp-nginx-1:/etc/nginx/ssl/ && docker cp lemp.docker-key.pem lemp-nginx-1:/etc/nginx/ssl/ && rm -f lemp.docker*.pem`;
        $output .= `docker exec -w /etc/nginx lemp-nginx-1 sed -i 's/#listen/listen/g ; s/#ssl/ssl/g' nginx.conf && docker exec lemp-nginx-1 nginx -s reload`;
        $this->comment($output);
        $this->comment('Server configurations setup complete.');

        $this->info('Setting up domain configuration..');
        $this->setupHosts();
        $this->comment('Domain configuration setup complete.');

        $this->info('Setting up database..');
        $password = env('DB_PASSWORD');
        $dbCheck = trim(`docker exec lemp-mariadb-1 mysql -u root -p$password -N -e 'show databases;' | grep --color=none lemp`);
        if($dbCheck != 'lemp'){
            $output = `docker exec lemp-mariadb-1 mysql -u root -p$password -e 'create database lemp;'`;
            $output .= `php artisan migrate`;
            $this->comment($output);
            $this->comment('Database setup complete.');
        } else
            $this->comment('Database already exists.');
    }
}
