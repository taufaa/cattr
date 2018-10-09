<?php

namespace Tests;

use Artisan;
use DatabaseSeeder;
use DatabaseTestsSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
  use CreatesApplication;

  public function setUp()
  {
    parent::setUp();
    Artisan::call('db:seed', ['--class' => DatabaseSeeder::class]);
  }

  public function getAdminToken()
  {
    $auth = [
      'login'     => 'admin@example.com',
      'password'  => 'admin'
    ];

    $response = $this->postJson('/api/auth/login', $auth);

    return $response->json('access_token');
  }
}
