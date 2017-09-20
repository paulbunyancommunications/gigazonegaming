<?php
/**
 * HomeRouteTest
 *
 * Created 4/7/16 11:18 AM
 * Tests for home route
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Test\Functional
 */

namespace Tests\Functional;
use App\Models\WpPost;
use Cache;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class HomeRouteTest extends \TestCase
{
    use DatabaseTransactions;

    public function tearDown()
    {
        parent::tearDown();
        exec('php artisan migrate:refresh');
    }
    
    /**
     * @test
     * @group homeRoute
     */
    public function it_has_updates_form_on_homepage()
    {
        $post = WpPost::where('post_title','Home')->where('post_status','publish')->first();
        $post->post_content = $post->post_content . '[update-sign-up]Signup For Updates[/update-sign-up]';
        $post->save();
        $_SERVER['HTTPS'] = 'on';
        $response = $this->call('GET', '/');
        $this->assertTrue($response->isOk());
        $this->assertNotFalse(strpos($response->getContent(), 'Signup For Updates')!==false, 'the front page has the updates sign up form');
    }

    /**
     * @test
     * @group homeRoute
     */
    public function it_has_a_cache_of_the_front_page()
    {
        $response = $this->call('GET', '/');
        $this->assertTrue($response->isOk());
        $this->assertTrue(Cache::has('front-page-from-wp'));
    }
}
