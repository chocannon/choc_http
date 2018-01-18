<?php
// +----------------------------------------------------------------------
// | 参数异常
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace App\Exceptions;

use Code;

class RouteException extends \Exception 
{
    protected $code = Code::ROUTE_UNFOUND;
}