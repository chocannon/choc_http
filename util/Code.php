<?php
// +----------------------------------------------------------------------
// | 返回码配置文件
// +----------------------------------------------------------------------
// | Author: qh.cao
// +----------------------------------------------------------------------
namespace Util;

class Code 
{
    const SUCCESS       = 0;   // 请求成功
    const REMOTE_ERROR  = 300; // 获取远程数据  
    const INVALID_PARAM = 400; // 参数无效
    const ROUTE_UNFOUND = 404; // 路由无效
    const SYSTEM_ERROR  = 500; // 服务器错误


    const UNAUTH        = 1000; // 未授权
    const INVALID_TOKEN = 1001; // 授权无效
}