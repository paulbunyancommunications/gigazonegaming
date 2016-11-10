<?php
/**
 * ${CLASS_NAME}
 *
 * Created 6/13/16 12:10 PM
 * Description of this file here....
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package App\Http\Requests
 * @subpackage Subpackage
 */

namespace App\Http\Requests;

/**
 * Mock is_user_logged_in
 * @return mixed
 */
function is_user_logged_in()
{
    return WpRequestsBase::$functions->is_user_logged_in();
}

/**
 * Mock is_super_admin
 * @return mixed
 */
function is_super_admin()
{
    return WpRequestsBase::$functions->is_super_admin();
}

/**
 * Mock is_user_admin
 * @return mixed
 */
function is_user_admin()
{
    return WpRequestsBase::$functions->is_user_admin();
}

use Mockery;

class WpRequestsBase extends \TestCase
{
    /**
     * @var \Mockery::mock;
     */
    public static $functions;
    
    public function __construct()
    {
        self::$functions = Mockery::mock();
    }
}
