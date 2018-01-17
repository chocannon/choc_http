<?php
// +----------------------------------------------------------------------
// | 异常控制器
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
class ErrorController extends Yaf\Controller_Abstract 
{
    public function errorAction($exception) 
    {
        if ($exception instanceof Yaf\Exception\StartupError) {
            throw new \Exception(1);
        }


        if ($exception instanceof Yaf\Exception\RouterFailed) {
            throw new \Exception(2);
        }


        if ($exception instanceof Yaf\Exception\DispatchFailed) {
            throw new \Exception(3);
        }


        if ($exception instanceof Yaf\Exception\LoadFailed) {
            throw new \Exception(4);
        }

        
        if ($exception instanceof Yaf\Exception\TypeError) {
            throw new \Exception(5);
        }
    }
}