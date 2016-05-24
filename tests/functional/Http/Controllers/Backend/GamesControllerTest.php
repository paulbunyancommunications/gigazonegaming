<?php

namespace Test\Functional\Http\Controllers\Backend;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Championship\Game;

class GamesControllerTest extends \TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;
    /**
     * @test
     * @covers \App\Http\Controllers\Frontend\Updates\UpdatesController::store
     */
    public function it_returns_a_success_when_gets_info_without_login__get_request()
    {
//        $faker = \Faker\Factory::create();

        $response = $this->call('GET', '/manage/game');
        dd($response);
//        $this->assertEquals(200, $response->getStatusCode());
//        $this->assertNotFalse(strpos(implode(' ', $this->success), 'There are no games yet') or strpos(implode(' ', $this->success), 'There are no games yet'));
    }

}
