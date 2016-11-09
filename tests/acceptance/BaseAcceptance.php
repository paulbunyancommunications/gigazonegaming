<?php

/**
 * Class BaseAcceptance
 */
class BaseAcceptance
{
    /**
     *
     */
    const TEXT_WAIT_TIMEOUT = 30;


    /**
     * @var Faker\Generator
     */
    public $faker;

    /**
     * @var array
     */
    protected $wpAdminUser = ['name' => 'tester', 'password' => '123abc234def', 'email' => 'tester@example.com'];


    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        // run migrations
        $this->resetDB($I);
        $I->runMigration($I);
        $this->faker = \Faker\Factory::create();
    }

    /**
     * @param AcceptanceTester $I
     */
    public function _after(AcceptanceTester $I)
    {
        // reset all the databases
        $this->resetDB($I);
        $I->runMigration($I);
    }

    /**
     * @param AcceptanceTester $I
     */
    private function resetDB(AcceptanceTester $I)
    {
        // reset all the databases
        $I->populateDatabase($I, [
            'server' => getenv('DB_HOST'),
            'user' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'database' => getenv('DB_DATABASE'),
            'dump' => 'database/dump/gzgaming_wp.sql',
        ]);
        $I->populateDatabase($I, [
            'server' => getenv('DB_HOST_CHAMP'),
            'user' => getenv('DB_USERNAME_CHAMP'),
            'password' => getenv('DB_PASSWORD_CHAMP'),
            'database' => getenv('DB_DATABASE_CHAMP'),
            'dump' => 'database/dump/gzgaming_champ_db.sql',
        ]);
    }

    /**
     * Create the test admin user
     */
    protected function createWpAdminUser(AcceptanceTester $I)
    {
        $I->runShellCommand('php artisan db:seed --class=WpTestAdminUserSeed');
    }

    /**
     * @param AcceptanceTester $I
     */
    protected function loginWithAdminUser(AcceptanceTester $I)
    {
        $this->createWpAdminUser($I);
        \Helper\Acceptance::loginToWordpress(
            $I,
            $this->wpAdminUser['name'],
            $this->wpAdminUser['password']
        );
    }

    /**
     * @param AcceptanceTester $I
     */
    protected function logoutOfWp(AcceptanceTester $I)
    {
        $I->amOnPage('/wp/wp-login.php?action=logout');
        $I->see('You are attempting to log out of');
        $I->executeJS('var button = document.getElementsByTagName("a");
                        for (var i = 0; i < button.length; i++) {
                            if (button[i].innerHTML === "log out") {
                                button[i].click();
                            }
        }');
        $I->waitForText('You are now logged out.', self::TEXT_WAIT_TIMEOUT);


    }

    protected function destroySelect2(AcceptanceTester $I)
    {
        $I->executeJS('$("select").each(function(ele){ $(this).select2("destroy"); });');
        $I->wait(1);
    }
}
