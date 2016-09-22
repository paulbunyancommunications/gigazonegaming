<?php

class BaseAcceptance
{
    const TEXT_WAIT_TIMEOUT = 30;

    public $faker;

    protected $wpAdminUser = ['name' => 'tester', 'password' => 'password', 'email' => 'tester@example.com'];


    public function _before(AcceptanceTester $I)
    {
        // run migrations
        $I->runMigration($I);
        $this->faker = \Faker\Factory::create();
    }

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

    protected function loginWithAdminUser(AcceptanceTester $I)
    {
        $this->createWpAdminUser($I);
        $I->amOnPage('/wp/wp-login.php?loggedout=true');
        $I->fillField(['id' => 'user_login'], $this->wpAdminUser['name']);
        $I->fillField(['id' => 'user_pass'], $this->wpAdminUser['password']);
        $I->click(['id' => 'wp-submit']);
        $I->see('Dashboard');
    }

    protected function logoutOfWp(AcceptanceTester $I)
    {
        $I->amOnPage('/wp/wp-login.php?action=logout');
        $I->see('You are attempting to log out of');
        $logoutLink = "t".time();
        $I->executeJS('var button = document.getElementsByTagName("a");
for (var i = 0; i < button.length; i++) {
    if (button[i].innerHTML === "log out") {
        button[i].click();
    }
}');
        $I->wait(5);
        $I->see('You are now logged out.');


    }
}
