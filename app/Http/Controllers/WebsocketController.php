<?php

namespace App\Http\Controllers;

use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use React\EventLoop\LoopInterface;

class WebsocketController extends Controller implements MessageComponentInterface{
    /**
     * @var LoopInterface
     */
    private $loop;

    public function setLoop(LoopInterface $loop){
        $this->loop = $loop;
    }

    /**
     * @var array $clients Connected browser clients viewing the current logs.
     */
    private array $clients = [];

    /**
     * @inheritDoc
     */
    function onOpen(ConnectionInterface $conn){
        $this->clients[$conn->resourceId] = compact('conn') + ['log' => null];
    }

    /**
     * @inheritDoc
     */
    function onClose(ConnectionInterface $conn){
        if(!isset($this->clients[$conn->resourceId]))
            return;
        if(!is_null($this->clients[$conn->resourceId]['log'])){
            $this->loop->removeReadStream($this->clients[$conn->resourceId]['log']);
            pclose($this->clients[$conn->resourceId]['log']);
        }
        unset($this->clients[$conn->resourceId]);
    }

    /**
     * @inheritDoc
     */
    function onError(ConnectionInterface $conn, \Exception $e){
        \Log::error($e->getMessage(), $e->getTrace());
    }

    /**
     * Handling incoming websocket messages and responding accordingly.
     *
     * @param ConnectionInterface $conn
     * @param MessageInterface $msg
     * @return void
     */
    public function onMessage(ConnectionInterface $conn, MessageInterface $msg){
        $msg = json_decode($msg, true);
        if($msg['action'] == 'PING'){
            $conn->send('PONG');
            return;
        }
        if($msg['action'] == 'LOG'){
            $this->clients[$conn->resourceId]['log'] = popen("tail -n 100 -f storage/logs/laravel.log", 'r');
            $this->loop->addReadStream($this->clients[$conn->resourceId]['log'], function($resource) use ($conn){
                if(is_resource($resource))
                    $conn->send(fgets($resource));
            });
        }
    }
}