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

            if ($attr->isVal() && $attr->isClass()) {
                $value = self::make($attr->getType(), $attr->getVal())->convertToObject();
            } else if ($attr->isVal() && $attr->isArrayClass()) {
                $value = [];
                foreach ($attr->getVal() as $k => $v) {
                    $value[$k] = self::make($attr->getArrayClassName(), $v)->convertToObject();
                }
            } else {
                $value = $attr->getVal();
            }

            $prop->setAccessible(true);

            $prop->setValue($instance, $value);
        }
        
        return $instance;
    }

    public static function make(string $className, array|object $params) : self {
        return new self($className, $params);
    }
}