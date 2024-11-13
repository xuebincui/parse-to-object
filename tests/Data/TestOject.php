<?php

namespace Tests\Data;

use ParseToObject\ParseAttr;

use ParseToObject\ParseToObject;

class TestOject {
    #[ParseAttr]
    public TestType $type = TestType::Normal;
    #[ParseAttr(name: "id")]
    public int $id;
    #[ParseAttr]
    public string $name;
    public string $age;

    use ParseToObject;
}