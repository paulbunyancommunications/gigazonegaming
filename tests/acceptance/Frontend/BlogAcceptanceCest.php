<?php

namespace Tests\Acceptance\Frontend;

class BlogAcceptanceCest extends \BaseAcceptance
{
    public function _before(\AcceptanceTester $I)
    {
        parent::_before($I);
        $postsCount = 3;
        $I->runQuery($I,
            [
                'server' => env('DB_HOST'),
                'user' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'database' => env('DB_DATABASE'),
            ],
            "UPDATE `wp_options` SET `option_value` = '" . $postsCount . "' WHERE `option_name` = 'posts_per_page'");


        $I->loginToWordpress($I, 'admin', 'password', 1);
        /** Make a bunch of posts so that the pagination will show up */
        for ($i = 0; $i < ($postsCount * 2); $i++) {
            $I->createAPost($I, [
                'title' => $this->faker->sentence(),
                'content' => $this->faker->paragraph(),
                'meta' => [['selectOption', '#cnr_post_parent', 'Blog']]
            ]);
        }
    }

    public function _after(\AcceptanceTester $I)
    {
        parent::_after($I);
    }

    /**
     * Test to see that pagination works in the blog section
     * @param \AcceptanceTester $I
     * @test
     * @group blog
     */
    public function testToSeeThatPaginationWorksInTheBlogSection(\AcceptanceTester $I)
    {
        $I->amOnPage('/blog');
        $I->click(['id' => 'pagination-2']);
        $I->wait(floor(\BaseAcceptance::TEXT_WAIT_TIMEOUT / 2));
        $I->dontSee('It looks like nothing was found at this location.');
        $I->see('Blog', '.tag-title .tag-title-text');
        $I->see('(Page 2)', '.tag-title .tag-title-text .paged');
        $I->seeElement('span#pagination-2');
    }
}
