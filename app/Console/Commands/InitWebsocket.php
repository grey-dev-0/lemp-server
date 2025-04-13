<?php

namespace App\Console\Commands;

use App\Http\Controllers\WebsocketController;
use Illuminate\Console\Command;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class InitWebsocket extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'websocket:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initiates the log viewing websocket server.';

    /**
     * Execute the console command.
     */
    public function handle(){
        $serverIp = trim(`docker inspect lemp-dns-1 | grep IPAddr | grep --color=none -oE '[0-9]+\.[0-9]+[^"]+'`);
        $handler = new WebsocketController;
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    $handler
                )
            ),
            9005, $serverIp
        );
        $handler->setLoop($server->loop);
        $server->run();
    }
}
