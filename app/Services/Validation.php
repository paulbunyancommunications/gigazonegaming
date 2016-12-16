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


use Illuminate\Validation\Validator;

/**
 * Class Validation
 * @package App\Services
 */
class Validation extends Validator
{

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return int
     */
    public function validateFloat($attribute, $value, $parameters)
    {
        //https://txt2re.com/index-php.php3?s=-1000.12345&1
        $float = '([+-]?\\d*\\.\\d+)(?![-+0-9\\.])';
        return preg_match('/'.$float.'/', $value);
    }
}
