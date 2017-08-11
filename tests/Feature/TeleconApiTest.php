<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeleconApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testTelecomApi()
    {
		$response = $this->get('/api/test');

		$response->assertStatus(200);
    }
}
