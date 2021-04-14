<?php

namespace Tests;

use Dive\Stateful\StatefulServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    protected function getPackageProviders($app)
    {
        return [StatefulServiceProvider::class];
    }

    protected function setUpDatabase($app)
    {
        $app->make('db')->connection()->getSchemaBuilder()->dropAllTables();

        /*
        require_once __DIR__.'/../database/migrations/create_laravel_stateful_table.php.stub';
        (new \CreatePackageTable())->up();
        */
    }
}
