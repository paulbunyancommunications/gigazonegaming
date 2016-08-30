<?php

class BaseAcceptanceCest
{
    const TEXT_WAIT_TIMEOUT = 30;

    public function _before(AcceptanceTester $I)
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
        $I->populateDatabase($I, [
            'server' => getenv('DB_HOST_AUTH'),
            'user' => getenv('DB_USERNAME_AUTH'),
            'password' => getenv('DB_PASSWORD_AUTH'),
            'database' => getenv('DB_DATABASE_AUTH'),
            'dump' => 'database/dump/gzgaming_sentinel.sql',
        ]);
        // run migrations
        $I->runMigration($I);
    }

    public function _after(AcceptanceTester $I)
    {
        // run migrations after test completes
        $I->runMigration($I);
    }
}