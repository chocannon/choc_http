<?php
// +----------------------------------------------------------------------
// | 封装Yaf\Config类获取ini配置文件
// +----------------------------------------------------------------------
// | Author: qh.cao
// +----------------------------------------------------------------------
namespace Util;

use RuntimeException;

class Curl{
    private static $instance = null;

    private $retry  = 0;
    private $option = [];
    private $info;
    private $data;
    private $error;

    /**
     * 获取类单例
     *
     * @return void
     */
    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * 获取响应信息
     *
     * @return void
     */
    public function info()
    {
        return $this->info;
    }

    /**
     * 获取响应数据
     *
     * @return void
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * 获取响应错误
     *
     * @return void
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * 构建请求OPTION
     *
     * @param mix $item
     * @param mix $value
     * @return void
     */
    public function option($item, $value = null)
    {
        if (is_array($item)) {
            foreach ($item as $key => $val) {
                $this->option[(string)$key] = $val;
            }
        } else {
            $this->option[(string)$item] = $value;
        }
        return $this;
    }

    /**
     * 构造请求URL
     *
     * @param string $url
     * @return void
     */
    public function url(string $url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $this->option['CURLOPT_URL'] = $url;
            return $this;
        }
        throw new RuntimeException("Target URL {$url} Not Valid");
    }

    /**
     * 构造get请求数据
     *
     * @param string $url
     * @return void
     */
    public function get(string $url = '')
    {
        if (!empty($url)) {
            $this->url($url);
        }
        $this->process();
        return empty($this->error) ? $this->data : false;
    }

    /**
     * 构造post请求数据
     *
     * @param array $data
     * @param string $url
     * @return void
     */
    public function post(array $data = [], string $url = '')
    {
        $this->option['CURLOPT_POST'] = true;
        $this->option['CURLOPT_POSTFIELDS'] = $data;
        if (!empty($url)) {
            $this->url($url);
        }
        $this->process();
        return empty($this->error) ? $this->data : false;
    }

    /**
     * 执行请求
     *
     * @return void
     */
    private function process()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        foreach($this->option as $key => $val) {
            curl_setopt($ch, constant(strtoupper($key)), $val);
        }
        
        $this->data  = (string) curl_exec($ch);
        $this->info  = curl_getinfo($ch);
        $this->error = curl_error($ch);
        curl_close($ch);

        if ($this->error && $this->retry) {
            $this->process($retry + 1);
        }
        $this->option = [];
    }
}