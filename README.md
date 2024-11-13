# ParseToObject

ParseToObject is a simple library to parse param (array/object) into object.

## Required

php >= 8.2

## Usage

```php
use ParseToObject\ParseToObject;
use ParseToObject\ParseAttr;

enum TestType: int {
    case Normal = 0;
    case Api = 1;
}

class TestSubOject {
    #[ParseAttr]
    public int $id;
    #[ParseAttr]
    public ?string $name;
}

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
```

```php
$sub = [
    'id' => 11,
    'name' => 'TestSub'
];
$params = [
    'id' => 1,
    'name' => 'Test',
    'age' => 20,
    'type' => 1,
    'sub' => $sub,
    'subList' => [
        [
            'id' => 12,
            'name' => 'TestSub12'
        ],
        [
            'id' => 13,
            'name' => 'TestSub13'
        ]
    ],
    'numberList' => [1, 2, 3],
    'delimiterStr' => 'a|b|c',
    'jsonStr' => json_encode($sub),
];
$testOject = TestOject::from($params);
or
ParseToObject\Parser::make(TestOject::class, $params)->convertToObject();
```

## ParseAttr Attribute

| attr | type | description | default |
|  :----:  |  :----:  |  ----  |  :----:  |
| **name** | string | param name | null |
| **type** | string | name data type | null |
| **isDefault** | bool | Is there a default value | false |
| **default** |  | default value | null |
| **required** | bool | param is required | false |
| **className** | string | when type is 'array' and className is not null, className is the class name of array item. | null |
| **sourceType** | string | source type, string (json-str,delimiter-str). | null |
| **delimiter** | string | Valid when sourceType is 'delimiter-str'. | "," |