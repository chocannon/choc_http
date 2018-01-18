<?php
// +----------------------------------------------------------------------
// | 参数异常
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace App\Exceptions;

use Code;

class RemoteException extends \Exception 
{
    protected $code = Code::REMOTE_ERROR;
}