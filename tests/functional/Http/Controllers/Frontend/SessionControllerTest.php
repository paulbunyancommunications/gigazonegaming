<?php
namespace Test\Functional\Http\Controllers\Frontend;

/**
 * Class SessionControllerTest
 * @package Test\Functional\Http\Controllers\Frontend
 */
class SessionControllerTest extends \TestCase
{

    /**
     * Tear down
     */
    public function tearDown()
    {
        parent::tearDown();

    }

    /**
     * Test getting CSRF token
     *
     * @test
     * @covers \App\Http\Controllers\Frontend\SessionController::csrfToken
     * @return void
     */
    public function it_gets_the_csrf_token()
    {
        $response = $this->call('GET', '/frontend/session/csrf');
        $this->assertSame($response->content(), csrf_token());
    }
}
