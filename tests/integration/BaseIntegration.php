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
        exec('mysql -h "'.env('DB_HOST').'" -u "'.env('DB_USERNAME').'" "-p'.env('DB_PASSWORD').'" "'.env('DB_DATABASE').'" < '.dirname(dirname(__DIR__)) . '/database/dump/gigazone_wp.sql 2> /dev/null');
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