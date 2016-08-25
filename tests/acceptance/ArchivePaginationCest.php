<?php


/**
 * Class paginationCest
 */
class ArchivePaginationCest
{
    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        $I->populateDatabase($I, [
            'server' => getenv('DB_HOST'),
            'user' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'database' => getenv('DB_DATABASE'),
            'dump' => 'database/dump/gzgaming_wp.sql',
        ]);
        $I->runMigration($I);
        exec('wp post generate --count=20 --path=public_html/wp >> /dev/null 2>&1');

    }

    /**
     * @param AcceptanceTester $I
     */
    public function _after(AcceptanceTester $I)
    {

        $I->runMigration($I);

    }

    /**
     * Check that pagination shows on archive pages
     *
     * @param AcceptanceTester $I
     */
    public function pagination_displays_on_archive(AcceptanceTester $I)
    {
        $I->wantTo('see that the pagination block shows on a archive list page');
        $I->amOnPage('/'.date('Y').'/'.date('m'));
        $I->seeElement(['class' => 'pagination']);
    }

    /**
     * Check that clicking on pagination sends to the right page
     *
     * @param AcceptanceTester $I
     */
    public function pagination_send_to_archive_page_on_click(AcceptanceTester $I)
    {
        $I->wantTo('see that the pagination block shows on a archive list page');
        $I->amOnPage('/'.date('Y').'/'.date('m'));
        $I->click(['id' => 'pagination-2']);
        $I->dontSee('It looks like nothing was found at this location.');
        $I->see('Archive');
        $I->see(date('F, Y'));
        $I->see('(Page 2)');
        $I->seeElement('span#pagination-2');

    }
}
