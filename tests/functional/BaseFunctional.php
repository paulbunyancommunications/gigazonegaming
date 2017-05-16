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
        exec('mysql -h "'.env('DB_HOST').'" -u "'.env('DB_USERNAME').'" "-p'.env('DB_PASSWORD').'" "'.env('DB_DATABASE').'" < '.dirname(dirname(__DIR__)) . '/database/dump/gigazone_wp.sql 2> nul');
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