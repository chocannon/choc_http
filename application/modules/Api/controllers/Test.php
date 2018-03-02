<?php
use Util\Config;
use Util\Logger;

class TestController extends Controller 
{
    public function testAction()
    {
        Logger::info("test test ");
        throw new \RuntimeException('aaaaa');
    }


    public function scoreAction()
    {
        $count = SampleModel::count();
        return $this->response([$count]);
    }
}