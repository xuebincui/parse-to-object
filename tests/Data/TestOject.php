<?php

namespace Tests\Data;

use ParseToObject\ParseAttr;

use ParseToObject\ParseToObject;

class TestOject {
    #[ParseAttr(name: "id")]
    public int $id;
    #[ParseAttr]
    public ?string $name;
    #[ParseAttr]
    public $age;
    #[ParseAttr]
    public TestType $type = TestType::Normal;
    #[ParseAttr(name: "sub")]
    public ?TestSubOject $subOject = null;
    #[ParseAttr(className: TestSubOject::class)]
    public array $subList = [];
    #[ParseAttr]
    public array $numberList = [];
    #[ParseAttr(name: "delimiterStr", sourceType: "delimiter-str", delimiter: "|")]
    public array $delimiterList = [];
    #[ParseAttr(name: "jsonStr", sourceType: "json-str")]
    public ?TestSubOject $jsonObj = null;
    
    
    public int $sex;

    use ParseToObject;
}