<?php

namespace ParseToObject;

trait ParseToObject {
    public static function from($params) {
        // return ParseToObjectFactory::convertToObject(get_called_class(), $params);
        return Parser::make(get_called_class(), $params)->convertToObject();
    }
}