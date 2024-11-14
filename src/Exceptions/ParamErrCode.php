<?php

namespace ParseToObject\Exceptions;

enum ParamErrCode: int {
    case Required = 1; // 参数必须存在
    case Missing = 2; // 参数不存在
    case NotNull = 3; // 值不能为null
    case ValueInvalid = 4; // 值无效

    public function getMessage(): string {
        return match ($this) {
            self::Required => 'required',
            self::Missing => 'missing',
            self::NotNull => 'cannot be null',
            self::ValueInvalid => 'value invalid',
        };
    }
}