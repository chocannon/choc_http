<?php
// +----------------------------------------------------------------------
// | Rpc服务
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Service;

use Exception;
use Coral\Utility\Package;
use Coral\Server\BaseServer;

class RpcServer extends BaseServer 
{
    protected $application = null;
    protected $processName = 'RpcServer';
    protected $port        = 9502;
    protected $serverType  = 'Tcp';

    public function onWorkerStart(\Swoole\Server $server, int $workerId) 
    {
        parent::onWorkerStart($server, $workerId);
        if(function_exists('opcache_reset')){
            opcache_reset();
        }
        $this->application = new \Yaf\Application(APPLICATION_PATH . "/conf/application.ini");
        ob_start();
        $this->application->bootstrap()->run();
        ob_end_clean();
    }

    public function onReceive(\Swoole\Server $serv, int $fd, int $reactorId, string $data) 
    {
        $receive = Package::decode($data);
        $request = new \Yaf\Request\Http($receive['url']);
        array_walk($receive['params'], function ($val, $key) use ($request) {
            $request->setParam($key, $val);
        });
        ob_start();
        try {
            $this->application->getDispatcher()->catchException(true)->dispatch($request);
            $ret = ob_get_contents();
        } catch (Exception $e) {
            $ret = json_encode([
                'code'    => $e->getCode(),
                'message' => $e->getMessage(),
                'result'  => []
            ], JSON_UNESCAPED_UNICODE);
        }
        ob_end_clean();
        // $ret = [
        //     'code' => '0',
        //     'msg'  => '888',
        //     'ret'  => [],
        // ];
        // try {       

        // } catch (Exception $e) {
        //     if ($e instanceof \Yaf\Exception) {
        //         $ret = json_encode([
        //             'code'    => '-1',
        //             'message' => 'API Unavailable',
        //             'result'  => []
        //         ], JSON_UNESCAPED_UNICODE);
        //         \Logger::error("receive:{receive}\r\ncode:{code}\r\nmessage:{message}\r\ntrace:{trace}", [
        //             'receive' => $receive,
        //             'code'    => $e->getCode(),
        //             'message' => $e->getMessage(),
        //             'trace'   => $e->getTrace(),
        //         ]);
        //     } else {
                
        //     }
        // }

        $serv->send($fd, Package::encode($ret));
    }
}