<?php

namespace ParseToObject;

use Attribute;


#[Attribute]
class ParseAttr {
    public ?string $name; // 字段名称
    public ?string $type; // 类型
    public bool $isDefault; // 是否有默认值
    public $default; // 默认值
    public bool $required; // 是否必填
    public ?arrayObjectClass $className; // 当类型是array时,类名,主要是针对数组中对象
    public ?string $sourceType; // 来源类型(json-str,delimiter-str)
    public ?string $delimiter; // 分隔符(默认：英文逗号),当来源类型为时有效。

    public function __construct(
        ?string $name = null,
        ?string $type = null,
        bool $isDefault = false,
        $default = null,
        bool $required = false,
        ?arrayOjectClass $className = null,
        string $sourceType = null,
        string $delimiter = ','
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->isDefault = $isDefault;
        $this->default = $default;
        $this->required = $required;
        $this->className = $className;
        $this->sourceType = $sourceType;
        $this->delimiter = $delimiter;
    }

    public function getName() {
        return $this->name;
    }

    public function getType() {
        return $this->type;
    }

    public function getDefault() {
        return $this->default;
    }

    public function isRequired() {
        return $this->required;
    }

    public function getArrayObjectClassName() {
        return $this->className;
    }

    public function isHasDefault() {
        return $this->isDefault;
    }

    /*
     * 是否是内置类型
     */
    public function isBuiltin() {
        return in_array($this->type, ['int', 'float', 'double', 'bool', 'string']);
    }

    /*
     * 是否是数组
     */
    public function isArrayObject() {
        return $this->type == 'array' && $this->className != null;
    }

    /*
     * 是否是对象
     */
    public function isObject() {
        return is_object($this->type);
    }

    public function isEnum() {
        return enum_exists($this->type);
    }

    function parseValBySource($val) {
        return match ($this->sourceType) {
            'json-str' => json_decode($val, true),
            'delimiter-str' => explode($this->delimiter, $val),
            default => $val,
        };
    }

}