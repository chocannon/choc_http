<?php
// +----------------------------------------------------------------------
// | 参数异常
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace App\Exceptions;

use Util\Code;

class ParamException extends \Exception 
{
    protected $code = Code::INVALID_PARAM;
}