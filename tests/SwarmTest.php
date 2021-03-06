<?php

namespace CrCms\Microservice\Client\Tests;

use Mockery\Mock;
use Illuminate\Cache\NullStore;
use PHPUnit\Framework\TestCase;
use Illuminate\Cache\Repository;
use CrCms\Microservice\Client\Services\Swarm;

/**
 * Class SwarmTest.
 */
class SwarmTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass(); // TODO: Change the autogenerated stub
    }

    public function testServices()
    {
        $app = \Mockery::mock('Illuminate\Contracts\Container\Container');

        $client = \Mockery::mock('CrCms\Foundation\Client\ClientManager');
        $client->shouldReceive('connection');
        $client->shouldReceive('handle')->with('services', ['method'=>'get'])->andReturn(
            $client
        );
        $client->shouldReceive('getContent')->andReturn(
            file_get_contents(__DIR__.'/swarm-service.json')
        );
        $client->shouldReceive('disconnection');
        $swarm = new Swarm(
            $app, global_config(), $client,
            new Repository(new NullStore())
        );
        $fpm = $swarm->services('php-fpm');
        $this->assertEquals(true, is_array($fpm));
        $this->assertEquals(true, is_array($fpm[0]));
        $this->assertEquals('php-fpm', $fpm[0]['name']);
        $this->assertEquals('php-fpm', $fpm[0]['host']);
    }
}
