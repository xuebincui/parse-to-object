<?php

namespace ParseToObject;

use Attribute;


#[Attribute]
class ParseAttr {
    private ?string $name; // 字段名称
    private ?string $type; // 类型
    private bool $isDefault; // 是否有默认值
    private $default; // 默认值
    private bool $required; // 是否必填
    private ?string $className; // 当类型是array时,类名,主要是针对数组中对象
    private ?string $sourceType; // 来源类型(json-str,delimiter-str)
    private string $delimiter; // 分隔符(默认：英文逗号),当来源类型为时有效。

    public function __construct(
        ?string $name = null,
        ?string $type = null,
        bool $isDefault = false,
        $default = null,
        bool $required = false,
        ?string $className = null,
        ?string $sourceType = null,
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

    public function getArrayClassName() {
        return $this->className;
    }

    public function isHasDefault() {
        return $this->isDefault;
    }

    /*
     * 是否是内置类型
     */
    public function isBaseType() {
        return in_array($this->type, ['int', 'float', 'double', 'bool', 'string']);
    }

    /*
     * 基础类型转换
     */
    public function baseTypeVal($val) {
        return match ($this->type) {
            'int' => intval($val),
            'float' => floatval($val),
            'double' => doubleval($val),
            'bool' => boolval($val),
            'string' => trim($val),
            default => $val
        };
    }

    /*
     * 是否是数组对象
     */
    public function isArrayClass() {
        return $this->type == 'array' && $this->className != null;
    }

    /*
     * 是否是对象
     */
    public function isClass() {
        return class_exists($this->type) && !enum_exists($this->type);
    }

    /*
     * 是否是枚举
     */
    public function isEnum() {
        return enum_exists($this->type);
    }

    /*
     * 根据来源类型，解析值
     */
    public function parseValBySource($val) {
        return match ($this->sourceType) {
            'json-str' => json_decode($val, true),
            'delimiter-str' => explode($this->delimiter, $val),
            default => $val,
        };
    }

}