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
        var_dump($testOject);

        $this->assertSame($testOject->id, $params['id']);
    }

    #[Test]
    public function testUninitialized(): void
    {

        $params = [
            'id' => 1,
            'name' => 'Test',
        ];

        $testOject = TestOject::from($params);
        $this->expectExceptionMessage('Typed property Tests\Data\TestOject::$age must not be accessed before initialization');

        $a = $testOject->age;
    }

}