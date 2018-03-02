<?php
// +----------------------------------------------------------------------
// | 助手函数
// +----------------------------------------------------------------------
// | Author: qh.cao
// +----------------------------------------------------------------------
namespace Util;

use Yaf\Registry;

class Helper
{
    public static function msectime() 
    {
       list($msec, $sec) = explode(' ', microtime());
       return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    }


    public static function hash(array $data)
    {
        $appKey  = Registry::get('config')->get('application.key');
        $appSolt = Registry::get('config')->get('application.solt');
        return md5($appSolt . sha1($appKey . serialize($data)));
    }
}