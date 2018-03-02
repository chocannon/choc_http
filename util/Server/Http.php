<?php
// +----------------------------------------------------------------------
// | Http服务
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Util\Server;

use Util\Code;
use Util\Output;
use Util\Logger;
use Coral\Server\BaseServer;
use App\Exceptions\ParamException;
use App\Exceptions\RouteException;
use App\Exceptions\RemoteException;

class Http extends BaseServer 
{
    protected $application = null;
    protected $processName = 'HttpServer';
    protected $serverType  = 'Http';

    public function onWorkerStart(\Swoole\Server $server, int $workerId) 
    {
        parent::onWorkerStart($server, $workerId);
        if(function_exists('opcache_reset')){
            opcache_reset();
        }
        $this->application = new \Yaf\Application(APPLICATION_PATH . "/config/application.ini");
        ob_start();
        $this->application->bootstrap()->run();
        ob_end_clean();
    }


    public function onRequest(\Swoole\Http\Request $request, \Swoole\Http\Response $response)
    {
        if ('/favicon.ico' === $request->server['path_info'] 
            || '/favicon.ico' === $request->server['request_uri']) {
            return $response->end();
        }

        try {
            $routeInfo  = \Yaf\Registry::get('routeDispatcher')->dispatch(
                $request->server['request_method'], $request->server['path_info']
            );
            if (\FastRoute\Dispatcher::FOUND !== $routeInfo[0]) {
                throw new \App\Exceptions\RouteException('API Unavailable');
            }

            \Yaf\Registry::set('SERVER', $request->server);
            \Yaf\Registry::set('HEADER', $request->header);
            $yafRequest = new \Yaf\Request\Http($routeInfo[1]);
            switch (strtoupper($request->server['request_method'])) {
                case 'GET':
                    $params = array_merge((array)$request->cookie, (array)$request->get, $routeInfo[2]);
                    break;
                case 'POST':
                    $params = array_merge((array)$request->cookie, (array)$request->post, $routeInfo[2]);
                    break;
                default:
                    $params = $routeInfo[2];
                    break;
            }
            array_walk($params, function ($val, $key) use ($yafRequest) {
                $yafRequest->setParam($key, $val);
            });
            ob_start();
            $this->application->getDispatcher()->dispatch($yafRequest);
            $ret = ob_get_contents();
        } catch (\Exception $e) {
            if ($e instanceof ParamException 
                || $e instanceof RouteException 
                || $e instanceof RemoteException) {
                $ret = Output::json($e->getCode(), $e->getMessage());
            } else {
                Logger::error("code:{code}\r\nmessage:{message}\r\ntrace:{trace}", [
                    'code'    => $e->getCode(),
                    'message' => $e->getMessage(),
                    'trace'   => $e->getTrace(),
                ]);
                $ret = Output::json(Code::SYSTEM_ERROR, 'System Error');
            }
        }
        ob_end_clean();
        $response->header('Server', 'somur');
        $response->header('Content-Type', 'application/json');
        $response->end($ret);
    }
}