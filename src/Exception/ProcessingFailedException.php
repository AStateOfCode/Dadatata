<?php

namespace Asoc\Dadatata\Exception;

final class ProcessingFailedException extends Exception
{

    private $stdErr;
    private $stdOut;

    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->stdOut = '';
        $this->stdErr = '';
    }

    public static function create($message = '', $code, $stdOut = '', $stdErr = '')
    {
        $exception         = new self($message, $code);
        $exception->stdOut = $stdOut;
        $exception->stdErr = $stdErr;

        return $exception;
    }

    /**
     * @return mixed
     */
    public function getStdErr()
    {
        return $this->stdErr;
    }

    /**
     * @return mixed
     */
    public function getStdOut()
    {
        return $this->stdOut;
    }
}