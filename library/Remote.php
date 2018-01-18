<?php
// +----------------------------------------------------------------------
// | 调用RPC客户端封装
// +----------------------------------------------------------------------
// | Author: qh.cao
// +----------------------------------------------------------------------
use Yaf\Registry;
use Service\RpcClient;
use App\Exceptions\RemoteException;

class Remote
{
    protected static $instance = null;

    public static function call(string $url, array $params)
    {
        try {
            if (null === self::$instance) {
                self::$instance = new RpcClient();
                self::$instance->setConfig(Config::get('server/client'));
            }
            $data = json_encode([
                'auth'   => [
                    'appKey'    => Registry::get('config')->get('application.key'),
                    'appSecret' => Helper::hash([
                        'url'    => $url,
                        'params' => $params,
                    ])
                ],
                'url'    => $url,
                'params' => $params,
            ]);
            $result = self::$instance->exec($data);
        } catch (\Exception $e) {
            Logger::error("send:{send}\r\ncode:{code}\r\nmessage:{message}\r\ntrace:{trace}\r\n", [
                'send'    => $data,
                'code'    => $e->getCode(),
                'message' => $e->getMessage(),
                'trace'   => $e->getTrace(),
            ]);
            throw new RemoteException('Get Remote Data Failure!');
        } 
        if (0 !== $result['code']) {
            Logger::error("send:{send}\r\ncode:{code}\r\nmessage:{message}\r\n", [
                'send'    => $data,
                'code'    => $result['code'],
                'message' => $result['message']
            ]);
            throw new RemoteException('Get Remote Data Failure!');
        }
        return $result['data'];
    }
}