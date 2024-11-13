<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

use Tests\Data\TestOject;

final class ParseTest extends TestCase
{
    #[Test]
    public function test(): void
    {

        $params = [
            'id' => 1,
            'name' => 'Test',
            'age' => 20,
            'type' => 1,
        ];

        $testOject = TestOject::from($params);

        $this->assertSame($testOject->id, $params['id']);
    }

    #[Test]
    public function test1(): void
    {

        $params = [
            'id' => "a",
            'name' => 'Test',
            'age' => 20,
            'type' => 1,
        ];

        $testOject = TestOject::from($params);

        $this->assertSame($testOject->id, 0);
        $this->assertSame($testOject->age, $params['age']);
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
        $this->expectExceptionMessage('param id cannot be null.');

        $params = [
            'id' => null,
            'name' => 'Test',
            'age' => 20,
        ];
        $testOject = TestOject::from($params);
    }

}