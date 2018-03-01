<?php
use Symfony\Component\Dotenv\Dotenv;

class IndexController extends Controller 
{
    public function indexAction() 
    {
        $dotenv = new Dotenv();
        $dotenv->load(APPLICATION_PATH . '/.env');
        $dbUser = getenv('DB_USERNAME');
        Logger::info(var_export($this->getRequest()->getParams(), true));
        return $this->response([$dbUser]);
    }


    public function testAction()
    {   
        $ret = Remote::call('index/index/index', ['title' => '3333344444']);
        return $this->response($ret);
    }
}