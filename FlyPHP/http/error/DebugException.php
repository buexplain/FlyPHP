<?php namespace fly\http\error;

use JsonSerializable;
use RuntimeException;

/**
 * 打印调试的异常
 * @package fly\exception
 */
class DebugException extends RuntimeException implements JsonSerializable
{
    public function __construct($data=[])
    {
        $this->code = 500;
        $this->message = $data;
        parent::__construct();
    }

    public function jsonSerialize()
    {
        return $this->message;
    }
}