<?php namespace fly\http\error;

use JsonSerializable;
use RuntimeException;

/**
 * 错误请求的异常
 * @package fly\exception
 */
class AbortException extends RuntimeException implements JsonSerializable
{
    /**
     * 响应格式
     * @var int
     */
    protected $format;

    /**
     * AbortException constructor.
     * @param string $message
     * @param int $format
     */
    public function __construct($message, $httpCode, $format)
    {
        $this->code = $httpCode;
        $this->message = $message;
        $this->format = $format;
        parent::__construct();
    }

    /**
     * 返回响应格式
     * @return int
     */
    public function getFormat()
    {
        return $this->format;
    }

    public function jsonSerialize()
    {
        return $this->message;
    }
}