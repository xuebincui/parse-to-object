<?php

namespace ParseToObject;


class Attr extends ParseAttr {
    public bool $isVal = false;
    public $val;

    public function __construct(
        \ReflectionProperty $prop
    ) {
        $attrs = $prop->getAttributes(ParseAttr::class);
        if (empty($attrs)) {
            throw new \Exception("ParseAttr is not found");
        }

        $arguments = [
            'name' => $prop->getName(),
            'type' => $prop->getType()->getName(),
        ];
        if ($prop->hasDefaultValue()) {
            $arguments['isDefault'] = true;
            $arguments['default'] = $prop->getDefaultValue();
        } else {
            $arguments['isDefault'] = false;
        }
        $arguments = array_merge($arguments, $attrs[0]->getArguments());
        parent::__construct(...$arguments);
    }

    public function setValByParams(array|object $params) : void {
        $name = $this->getName();

        if (!$this->isParamsExist($params)) {
            if ($this->isRequired()) {
                throw new \Exception("param {$name} is required");
            } else if ($this->isHasDefault()) {
                $this->val = $this->getDefault();
            } else {
                throw new \Exception("param {$name} is missing");
            }
        } else {
            $this->isVal = true;
            if ($this->isEnum()) {
                try {
                    $this->val = $this->getType()::from($this->getParamsVal($params, $name));
                } catch (\Error $e) {
                    throw new \Exception("param {$name} 's value is valid");
                }
            } else {
                $this->val = $this->parseValBySource($this->getParamsVal($params, $name));
            }
        }
    }

    public function getVal() {
        return $this->val;
    }

    public function isVal() : bool {
        return $this->isVal;
    }

    private function isParamsExist(array|object $params) {
        $name = $this->getName();

        return is_object($params) ? property_exists($params, $name) : array_key_exists($name, $params);
    }

    private function getParamsVal(array|object $params) {
        $name = $this->getName();

        return is_array($params) ? $params[$name] : $params->{$name};
    }
}