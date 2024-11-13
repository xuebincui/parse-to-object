<?php

namespace ParseToObject;

class Parser {
    private string $className;
    private array|object $params;

    public function __construct(string $className, array|object $params) {
        $this->className = $className;
        $this->params = $params;
    }

    /**
     * @return classNameObject
     */ 
    public function convertToObject() {
        $class = new \ReflectionClass($this->className);
        $instance = $class->newInstance();
        $props = $class->getProperties();
        foreach ($props as $prop) {
            try {
                $attr = new Attr($prop);
            } catch (\Exception $e) {
                continue;
            }

            $attr->setValByParams($this->params);

            if ($attr->isVal() && $attr->isObject()) {
                $value = self::make($attr->getType(), $value)->convertToObject();
            } else if ($attr->isVal() && $attr->isArrayObject()) {
                $value = [];
                foreach ($attr->getVal() as $k => $v) {
                    $value[$k] = self::make($attr->getArrayObjectClassName(), $v)->convertToObject();
                }
            } else {
                $value = $attr->getVal();
            }

            $prop->setAccessible(true);

            try {
                $prop->setValue($instance, $value);
            } catch (\TypeError $e) {
                throw new \Exception("param {$attr->getName()} 's data type is illegal.");
            }
        }
        
        return $instance;
    }

    public static function make(string $className, array|object $params) : self {
        return new self($className, $params);
    }
}