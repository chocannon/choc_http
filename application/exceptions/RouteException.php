<?php
// +----------------------------------------------------------------------
// | 参数异常
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace App\Exceptions;

use Util\Code;

class RouteException extends \Exception 
{
    protected $code = Code::ROUTE_UNFOUND;
}