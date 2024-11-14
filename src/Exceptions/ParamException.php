<?php

namespace ParseToObject\Exceptions;

class ParamException extends \Exception
{
    private string $name;
    private ParamErrCode $errCode;

    public function __construct(string $name, ParamErrCode $errCode)
    {
        $this->name = $name;
        $this->errCode = $errCode;

        parent::__construct("param {$name} is {$errCode->getMessage()}");
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getErrCode() : ParamErrCode {
        return $this->errCode;
    }
}