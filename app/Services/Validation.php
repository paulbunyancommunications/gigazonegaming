<?php
/**
 * Validation
 *
 * Created 5/27/16 8:40 AM
 * Class for holding custom validation methods
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package App\Services
 */

namespace App\Services;

class Validation extends \Illuminate\Validation\Validator
{

    public function validateFloat($attribute, $value, $parameters)
    {
        //https://txt2re.com/index-php.php3?s=-1000.12345&1
        $float = '([+-]?\\d*\\.\\d+)(?![-+0-9\\.])';
        return preg_match('/'.$float.'/', $value);
    }
}
