<?php

if(!function_exists('add_host')){
    /**
     * Adds the provided custom domain to host system's hosts file to enable local access to it.
     *
     * @param string $host The domain to add to host system's hosts file.
     * @param string $service The service that the domain should point to.
     * @return void
     */
    function add_host(string $host, string $service = 'nginx'): void{
        $serverIp = trim(`docker inspect lemp-{$service}-1 | grep IPAddr | grep --color=none -oE '[0-9]+\.[0-9]+[^"]+'`);
        $hostsFile = fopen('/etc/host.hosts', 'r');
        $ips = [];
        $done = false;
        while(($ip = fgets($hostsFile)) !== false){
            $ip = trim($ip);
            if(str_contains($ip, $host))
                return;
            if(!str_contains($ip, $serverIp)){
                $ips[] = $ip;
                continue;
            }
            $hosts = preg_split('# +#u', $ip);
            $hosts[] = $host;
            $ips[] = implode(' ', $hosts);
            $done = true;
        }
        fclose($hostsFile);
        if(!$done)
            $ips[] = "$serverIp $host";
        file_put_contents('/etc/host.hosts', implode(PHP_EOL, $ips));
    }
}