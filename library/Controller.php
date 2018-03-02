<?php
use Util\Output;
use Util\Validation;
use Yaf\Controller_Abstract;

abstract class Controller extends Controller_Abstract
{
    public function init() 
    {
        list($state, $msg) = Validation::check($this->getRequest());
        if (false === $state) {
            throw new App\Exceptions\ParamException($msg);
        }
    }

    
    /**
     * 响应客户端
     *
     * @param mix $data 响应数组
     * @return void
     */
    protected function response($data) 
    {
        $response = $this->getResponse();
        $response->setBody(Output::json($data));
        $response->response();
    }
}