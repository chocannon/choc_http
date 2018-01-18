<?php

class IndexController extends Controller 
{
    public function indexAction() 
    {
        Logger::info(var_export($this->getRequest()->getParams(), true));
        return $this->response(['hello world']);
    }


    public function testAction()
    {   
        $ret = Remote::call('index/index/index', ['title' => '3333344444']);
        return $this->response($ret);
    }
}