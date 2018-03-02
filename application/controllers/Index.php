<?php
use Util\Config;
use Util\Logger;

class IndexController extends Controller 
{
    public function indexAction() 
    {
        $a = SampleModel::count();
        
        
        $dbUser = Config::arr('database');
        Logger::info('3333333');
        return $this->response([$a]);
    }


    public function testAction()
    {
        throw new \RuntimeException('aaaaa');
        var_dump(\Yaf\Registry::get('routeDispatcher'));
    }
}