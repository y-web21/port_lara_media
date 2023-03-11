<?php

namespace Tests\Unit;

use App\Library\Api;
use App\Library\Covid19JpApi;
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
        $c19 = new Covid19JpApi('storage/api','s');
        // $api = new Api('url__');
        // $url = $this->getProperty($api, 'url');

        // $this->assertSame(['url__'], $url);
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
