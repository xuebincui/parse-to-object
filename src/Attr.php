<?php

namespace ParseToObject;


use ParseToObject\Exceptions\ParamException;
use ParseToObject\Exceptions\ParamErrCode;


class Attr extends ParseAttr {
    private bool $isVal = false;
    private $val;
    private bool $allowsNull = true;

    public function __construct(
        \ReflectionProperty $prop
    ) {
        $attrs = $prop->getAttributes(ParseAttr::class);
        if (empty($attrs)) {
            throw new \Exception("ParseAttr is not found");
        }

        $arguments = [
            'name' => $prop->getName(),
            'type' => $prop->getType() ? $prop->getType()->getName() : 'mixed',
        ];
        if ($prop->hasDefaultValue()) {
            $arguments['isDefault'] = true;
            $arguments['default'] = $prop->getDefaultValue();
        } else {
            $arguments['isDefault'] = false;
        }
        $arguments = array_merge($arguments, $attrs[0]->getArguments());
        parent::__construct(...$arguments);

        $this->allowsNull = $prop->getType() ? $prop->getType()->allowsNull() : true;
    }

    public function setValByParams(array|object $params) : void {
        $name = $this->getName();

        if (!$this->isParamsExist($params)) {
            if ($this->isRequired()) {
                throw new ParamException($name, ParamErrCode::Required);
            } else if ($this->isHasDefault()) {
                $this->val = $this->getDefault();
            } else {
                throw new ParamException($name, ParamErrCode::Missing);
            }
        } else {
            $this->isVal = true;
            if ($this->isEnum()) {
                try {
                    $this->val = $this->getType()::from($this->getParamsVal($params, $name));
                } catch (\Error $e) {
                    throw new ParamException($name, ParamErrCode::ValueInvalid);
                }
            } else {
                $this->val = $this->parseValBySource($this->getParamsVal($params, $name));
            }
        }

        $this->checkTypeAndVal();
    }

    private function checkTypeAndVal() {
        if ($this->val === null) {
            if ($this->allowsNull) {
                return;
            } else {
                throw new ParamException($this->getName(), ParamErrCode::NotNull);
            }
        }

        $this->isBaseType() && $this->val = $this->baseTypeVal($this->val);
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