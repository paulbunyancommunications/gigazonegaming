<?php
/**
 * ${CLASS_NAME}
 *
 * Created 9/6/16 10:12 AM
 * Description of this file here....
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package ${NAMESPACE}
 * @subpackage Subpackage
 */

// Application environment
$context['APP_ENV'] = env('APP_ENV', 'local');

// Google reCaptcha public key
$context['GOOGLE_RECAPTCHA_PUBLIC'] = env('GOOGLE_RECAPTCHA_PUBLIC', 'google-recaptcha-public-key-here');
