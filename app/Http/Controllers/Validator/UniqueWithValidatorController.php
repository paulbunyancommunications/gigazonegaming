<?php

namespace App\Http\Controllers\Validator;

use App\Http\Controllers\Controller;

class UniqueWithValidatorController extends Controller
{
    /*
     *
     * @param $attribute "selected attribute ie: 'name" => ...,
     * @param $value "value passed from request for this exact element",
     * @param $parameters "parameters passed along:
     *              1_ connection.table or table",
     *              2_ column to look at in db for the passed attribute
     *              3_ 4_ 5_ and so on will be like such:
     *                       'self_' if you don't want to pass the key again or if the value of the column is the same to the attribute passed,
     *                       '= tableColumnName' if the name of the key with the "unique with" constrain name is different to the table column name,
     *                       'key_passed = table_column' for all following request keys that also differ from the table column name
     *                       'key_passed' pass for all the request keys matching the table column
     *                       to any of them you can add > to add a value that you could get by the request and you want to add to ir as if you received a name but you want to pass the id of that object instead of the name
     *
     *                  !all spaces will be trimmed before and after so the "=" can or not have space before or after
     * @param $validator "we wont use this as all things inside it are protected",
     * @notAParam $_REQUEST 'request holds all the passed values so we will use this to match all keys to the respective values and prevent injections :) '
     * @return boolean
     */
    public function validateComposeKey($attribute, $value, $parameters, $validator)
    {
        $counter = count($parameters); // this should be quick, there should be less than 3 parameters and no more than 3 constraints in the unique compose key, either way less than 20 will still be pretty fast counting

//        dd(
//            "selected attribute",
//            $attribute,
//            "value passed",
//            $value,
//            "parameters passed along",
//            $parameters,
//            "validator",
//            $validator,
//            'request',
//            $_REQUEST,
//            'counter',
//            $counter
//        );

        if ($counter > 1) {
            $connectionDbTable = trim($parameters[0]);
            $dbColumn = trim($parameters[1]);
            $query = "";
            //create table where for each so we see through the elements if exist return false is not return true
            $theConnectionArray = explode('.',$connectionDbTable);
            if ( count( $theConnectionArray ) == 1 ) {
                $query = \DB::table($connectionDbTable[0]);
            }elseif ( count( $theConnectionArray ) == 2 ) {
                $query = \DB::connection($theConnectionArray[0])->table($theConnectionArray[1]);
            }
            $forQuery = [];
            for ($i=1; $i < $counter; $i++) {
                $equal = strpos($parameters[$i], '=');
                $value_exists = strpos($parameters[$i], '>');
                $col="";
                $val="";
                if( $equal === false AND $value_exists === false ){ // request key is same that column name
                    $col = trim($parameters[$i]);
                    if($col != 'self_') {
                        $val = trim($_REQUEST[$col]);
                    }else{
                        $col = trim($attribute);
                        $val = trim($value);
                    }
                }elseif( $equal === false AND $value_exists !== false ){ // request key is same that column name
                        $expression2 = explode('>', $parameters[$i]); //there is no value required for the key passed we care about what row was selected and what value was passed
                        $col = trim($expression2[0]);
                        $val = trim($expression2[1]);
                }else{
                    $expression = explode('=', $parameters[$i]);
                    if($equal == 0 ){ //=column
                        if($value_exists === false ) {//=column
                            $col = trim($expression[0]);
                            $val = trim($value);
                        }else{//=column>value
                            $expression2 = explode('>', $expression[0]); //there is no value required for the key passed we care about what row was selected and what value was passed
                            $col = trim($expression2[0]);
                            $val = trim($expression2[1]);
                        }
                    }else{//key=column>value
                        if($value_exists === false ){
                            $col = trim($expression[1]);
                            $val = $_REQUEST[trim($expression[0])];
                        }else{
                            $expression2 = explode('>', $expression[1]);// $expression 1 because 0 was the key and we wont use that one there is no value required for the key passed we care about what row was selected and what value was passed
                            $col = trim($expression2[0]);
                            $val = trim($expression2[1]);
                        }
                    }
                }
                if( $col != "" and $val != "" ) {
                    array_push($forQuery, [$col, '=', $val]);
                }
            }
            $exists = !($query->where($forQuery)->exists());

            return $exists;
        }
        return false;
    }
}
