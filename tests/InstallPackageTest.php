<?php

namespace Tests;

use function Pest\Laravel\artisan;

afterEach(function () {
    file_exists(config_path('laravel-stateful.php')) && unlink(config_path('laravel-stateful.php'));
    array_map('unlink', glob(database_path('migrations/*_create_laravel_stateful_table.php')));
});

it('copies the config', function () {
    artisan('laravel-stateful:install')->execute();

    expect(file_exists(config_path('laravel-stateful.php')))->toBeTrue();
});

it('copies the migration', function () {
    artisan('laravel-stateful:install')->execute();

    expect(glob(database_path('migrations/*_create_laravel_stateful_table.php')))->toHaveCount(1);
});
