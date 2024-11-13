# ParseToObject

ParseToObject is a simple library to parse param (array/object) into object.

## Required

php >= 8.2

## Usage

```php
use ParseToObject\ParseToObject;
use ParseToObject\ParseAttr;

class TestOject {
    #[ParseAttr(name: "id")]
    public int $id;
    #[ParseAttr]
    public string $name;

    use ParseToObject;
}

$params = [
    'id' => 1,
    'name' => 'Test',
    'age' => 20,
    'type' => 1,
];

$testOject = TestOject::from($params);
or
ParseToObject\Parser::make(TestOject::class, $params)->convertToObject();
```

## ParseAttr Attribute

**name**: param name. default: null
**type**: name data type. default: null
**isDefault**: Is there a default value. default: false
**default**: default value. default: null
**required**: param is required. default: false
**className**: when type is 'array' and className is not null, className is the class name of array item. default: null
**sourceType**: source type, string (json-str,delimiter-str). default: null
**delimiter**:  Valid when sourceType is 'delimiter-str'. default: ","
