<?php

namespace Tests\Unit;

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
        $c19api = new Covid19JpApi(
            './storage/api',
            logPath: './storage/api/log.log',
        );
        var_dump($c19api->prefectures()->get());
        var_dump($c19api->prefectures()->setPrefecture('1')->get());
        var_dump($c19api->total()->get());
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
