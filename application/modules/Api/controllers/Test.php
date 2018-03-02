<?php
use Util\Config;
use Util\Logger;
use Util\Curl;
use App\Repositories\Test\PushPlan;

class TestController extends Controller 
{
    public function testAction()
    {
        Logger::info("test test ");
        throw new \RuntimeException('aaaaa');
    }


    public function scoreAction()
    {
        // $count = \Yaf\Registry::get('HEADER');
        $count = Curl::instance()
            ->option([
                'CURLOPT_HTTPHEADER' => [
                    'x-somur-cid:1439da3f53bc45079fc7b59a9ae33726'
                ]
            ])
            ->url("http://39.106.183.235:8118/samples")
            ->post();
        return $this->response($count);
    }
}