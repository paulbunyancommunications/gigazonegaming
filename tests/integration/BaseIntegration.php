<?php
/**
 * BaseIntegrationTest
 *
 * Created 8/31/16 10:33 AM
 * Base setup and tear down for Integration tests
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Tests\Integration
 * @subpackage Subpackage
 */

namespace Tests\Integration;

class BaseIntegration extends \TestCase
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
        exec('php artisan migrate:refresh');
    }

    /**
     * Tear down test
     */
    public function tearDown()
    {
        parent::tearDown();
        exec('php artisan migrate:refresh');
    }
}
