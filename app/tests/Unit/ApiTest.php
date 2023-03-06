<?php

namespace Tests\Unit;

use App\Library\Api;
use ReflectionClass;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $api = new Api('url__');
        $url = $this->getProperty($api, 'url');

        $this->assertSame(['url__'], $url);
    }

    /**
     * get private or protected property
     *
     * @param object $object
     * @param string $property
     * @return mixed
     */
    private function getProperty(object $object, string $property)
    {
        $reflectedClass = new ReflectionClass($object);
        $reflected = $reflectedClass->getProperty($property);
        $reflected->setAccessible(true);
        return $reflected->getValue($object);
    }
}
