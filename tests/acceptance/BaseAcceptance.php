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
        exec('php artisan cache:clear');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function _after(AcceptanceTester $I)
    {
        // reset all the databases
        $this->resetDB($I);
        $I->runMigration($I);
        exec('php artisan cache:clear');
    }

    /**
     * @param AcceptanceTester $I
     */
    protected function resetDB(AcceptanceTester $I)
    {
        // reset all the databases
        $I->populateDatabase($I, [
            'server' => env('DB_HOST'),
            'user' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'database' => env('DB_DATABASE'),
            'dump' => 'database/dump/gigazone_wp.sql',
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

    /**
     * Populate db with test seeder
     */
    protected function populateDB(AcceptanceTester $I)
    {
        exec('php artisan db:seed --class=DatabaseSeeder');
    }

    protected function GetToken(){
        /*gets the inbox_id from mail trap*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://mailtrap.io/api/v1/inboxes?api_token=".env("MAIL_TRAP_API", "122ed35b015da58276e95c8d8cb81fee"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        $inbox = json_decode($response);
        $inbox_id = $inbox[0]->id;
        /*gets the password from the message sent*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://mailtrap.io/api/v1/inboxes/".$inbox_id."/messages?api_token=".env("MAIL_TRAP_API", "122ed35b015da58276e95c8d8cb81fee"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        $inboxMessage = json_decode($response);
        $messageID = $inboxMessage[0]->id;
        $message = $inboxMessage[0]->html_body;
        $tokenString = explode('/',$message);
        $tokenArray = explode('">',$tokenString[8]);
        $token = $tokenArray[0];
        /*deletes the message sent*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://mailtrap.io/api/v1/inboxes/".$inbox_id."/messages/".$messageID);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        $response = curl_exec($ch);
        curl_close($ch);
        return $token;

    }
}
