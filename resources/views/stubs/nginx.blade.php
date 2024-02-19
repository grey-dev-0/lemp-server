server {
    listen 80;
    listen [::]:80;
@if($tls)
    listen 443 ssl;
    listen [::]:443 ssl;
@endif
    server_name {{$serverName}};
@if($tls)
    ssl_certificate /etc/nginx/ssl/{{$serverName}}.pem;
    ssl_certificate_key /etc/nginx/ssl/{{$serverName}}-key.pem;
    ssl_prefer_server_ciphers on;
@endif
@switch($type)
    @case(\App\Models\Project::DYNAMIC_PHP)
    root /home/projects/{{$docRoot}};
    index index.php;
    @break
    @case(\App\Models\Project::DYNAMIC_LARAVEL)
    root /home/projects/{{$docRoot}}/public;
    index index.php;
    @break
    @default
    root /home/projects/{{$docRoot}};
    index index.html;
@endswitch
    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }
@if(in_array($type, [\App\Models\Project::DYNAMIC_PHP, \App\Models\Project::DYNAMIC_LARAVEL]))
    include common/php-fpm.conf;

    location ~ \.env$ {
        deny all;
    }
@endif
}