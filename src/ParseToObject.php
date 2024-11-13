<?php

namespace ParseToObject;

trait ParseToObject {
    public static function from($params) {
        return Parser::make(get_called_class(), $params)->convertToObject();
    }
}