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
            ->post([
                'sm_sign' => '29D32360D69A4A72CBDC823104AC6A40',
                'sm_time' => 1514957857,
                'sm_start_date' => 1114957857,
                'sm_end_date' => 1514957857
            ]);
        return $this->response(SampleModel::count());
    }
}