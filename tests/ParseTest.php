<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

use Tests\Data\TestOject;
use ParseToObject\Exceptions\ParamException;
use ParseToObject\Exceptions\ParamErrCode;

final class ParseTest extends TestCase
{
    #[Test]
    public function testBase(): void
    {
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

        $this->assertSame($testOject->id, $params['id']);
        $this->assertSame($testOject->age, $params['age']);

        $this->assertSame($testOject->subOject->id, $params['sub']['id']);
        $this->assertSame(count($testOject->subList), count($params['subList']));
        $this->assertSame($testOject->numberList, $params['numberList']);
        $this->assertSame($testOject->delimiterList, explode('|', $params['delimiterStr']));
        $this->assertSame($testOject->jsonObj->id, $sub['id']);
    }

    #[Test]
    public function testType(): void
    {
        $params = [
            'id' => "a",
            'name' => 'Test',
            'age' => 20,
            'type' => 1,
        ];
        $testOject = TestOject::from($params);

        $this->assertSame($testOject->id, 0);
        
    }

    #[Test]
    public function testUninitialized(): void
    {
        $params = [
            'id' => 1,
            'name' => 'Test',
            'age' => 20,
        ];

        $testOject = TestOject::from($params);
        $this->expectExceptionMessage('Typed property Tests\Data\TestOject::$sex must not be accessed before initialization');

        $sex = $testOject->sex;
    }

    #[Test]
    public function testNull(): void
    {
        $params = [
            'id' => null,
            'name' => 'Test',
            'age' => 20,
        ];
        try {
            $testOject = TestOject::from($params);
        } catch (ParamException $e) {
            $this->assertSame($e->getName(), "id");
            $this->assertSame($e->getErrCode(), ParamErrCode::NotNull);
            $this->assertSame($e->getMessage(), "param id is cannot be null");
        }
    }

}