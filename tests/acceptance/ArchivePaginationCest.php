<?php


/**
 * Class paginationCest
 */
class ArchivePaginationCest extends BaseAcceptance
{
    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
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
        for($i=0; $i < ($postsCount * 2); $i++) {
            $I->createAPost($I, $this->faker->sentence(), $this->faker->paragraph());
        }

    }

    /**
     * @param AcceptanceTester $I
     */
    public function _after(AcceptanceTester $I)
    {

        parent::_after($I);

    }

    /**
     * Check that pagination shows on archive pages
     *
     * @param AcceptanceTester $I
     * @group archive
     * @test
     */
    public function checkThatPaginationShowsOnArchivePages(AcceptanceTester $I, $scenario)
    {
        $I->amOnPage('/'.date('Y').'/'.date('m'));
        $I->seeElement(['class' => 'pagination']);
    }

    /**
     * Check that clicking on pagination sends to the right page
     * @param AcceptanceTester $I
     * @group archive
     * @test
     */
    public function checkThatClickingOnPaginationSendsToTheRightPage(AcceptanceTester $I, $scenario)
    {
        $I->amOnPage('/'.date('Y').'/'.date('m'));
        $I->click(['id' => 'pagination-2']);
        $I->wait(floor(BaseAcceptance::TEXT_WAIT_TIMEOUT / 2));
        $I->see('(Page 2)');
        $I->dontSee('It looks like nothing was found at this location.');
        $I->see('Archive');
        $I->see(date('F, Y'));
        $I->seeElement('span#pagination-2');
    }
}
