<?php
/**
 * BaseFunctional
 *
 * Created 8/31/16 11:59 AM
 * Bootstrap for functional tests
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Test\Functional
 */

namespace Test\Functional;

class BaseFunctional
{

    /**
     * @var \Faker\Factory::create
     */
    protected $faker;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        exec('mysql -h "'.getenv('DB_HOST').'" -u "'.getenv('DB_USERNAME').'" "-p'.getenv('DB_PASSWORD').'" "'.getenv('DB_DATABASE').'" < '.dirname(dirname(__DIR__)) . '/database/dump/gzgaming_wp.sql 2> nul');
        exec('mysql -h "'.getenv('DB_HOST_CHAMP').'" -u "'.getenv('DB_USERNAME_CHAMP').'" "-p'.getenv('DB_PASSWORD_CHAMP').'" "'.getenv('DB_DATABASE_CHAMP').'" < '.dirname(dirname(__DIR__)) . '/database/dump/gzgaming_champ_db.sql 2> nul');
    }

    /**
     * Set Up test
     */
    public function setUp()
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    /**
     * Tear down test
     */
    public function tearDown()
    {
        parent::tearDown();
    }
}