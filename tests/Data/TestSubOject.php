<?php

namespace Tests\Data;

use ParseToObject\ParseAttr;

class TestSubOject {
    #[ParseAttr]
    public int $id;
    #[ParseAttr]
    public ?string $name;
}