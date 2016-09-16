<?php

class BaseAcceptance
{
    const TEXT_WAIT_TIMEOUT = 30;

    public function _before(AcceptanceTester $I)
    {
        // run migrations
        $I->runMigration($I);
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
}
