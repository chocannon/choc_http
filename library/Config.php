<?php
// +----------------------------------------------------------------------
// | 封装Yaf\Config类获取ini配置文件
// +----------------------------------------------------------------------
// | Author: qh.cao
// +----------------------------------------------------------------------
use RuntimeException;

class Config 
{
    const CONF_PATH = APPLICATION_PATH . '/conf/';

    /**
     * 解析ini文件
     * @param  String      $ini ini文件
     * @param  String|null $sub key
     * @return array 
     */
    public static function ini(String $ini, String $sub = null) 
    {
        $iniFile = self::CONF_PATH . $ini . '.ini';
        if (!file_exists($iniFile)) {
            throw new RuntimeException("Config File {$iniFile} Not Found!");
        }

        $config = new Yaf\Config\Ini($iniFile, ini_get('yaf.environ'));
        $result = (null === $sub) ? $config->get() : $config->get($sub);
        return ($result instanceof Yaf\Config\Ini) ? $result->toArray() : $result;
    }


    /**
     * 解析数组文件
     * @param  String      $arr 数组文件
     * @param  String|null $sub key
     * @return [type] 
     */
    public static function arr(String $arr, String $sub = null)
    {
        $arrFile = self::CONF_PATH . $arr . '.php';
        if (!file_exists($arrFile)) {
            throw new RuntimeException("Config File {$arrFile} Not Found!");
        }

        $config = require $arrFile;
        return (null === $sub) ? $config : (isset($config[$sub]) ? $config[$sub] : []);
    }
}