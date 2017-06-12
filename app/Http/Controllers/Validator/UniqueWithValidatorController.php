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
            //niqueWidth:mysql_champ.teams,=name,tournament_id>'.$tournament_id
            for ($i=1; $i < $counter; $i++) {
                $equal = strpos($parameters[$i], '=');
                $value_exists = strpos($parameters[$i], '>');
                $col="";
                $val="";
                if( $equal === false AND $value_exists === false ){ // request key is same that column name
                    $col = trim($parameters[$i]);
                    if($col != 'self_') {
                        $val = trim($_REQUEST[$col]);
                    }else{ //self_ same attribute and val passed
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
                            if($expression[0] != '') {
                                $col = trim($expression[0]);
                            }elseif($expression[1] != '') {
                                $col = trim($expression[1]);
                            }elseif($expression[2] != '') {
                                $col = trim($expression[2]);
                            }else{ $col = '';}
                            $val = trim($value);
                        }else{//=column>value
                            if($expression[0] != '') {
                                $expression2 = explode('>', $expression[0]);
                            }elseif($expression[1] != '') {
                                $expression2 = explode('>', $expression[1]);
                            }elseif($expression[2] != '') {
                                $expression2 = explode('>', $expression[2]);
                            } //there is no value required for the key passed we care about what row was selected and what value was passed
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
/**

https://gigazonegaming.localhost/app/lol-team-sign-up?tournament=Tester_Tournament_Unique_Width_A&fields%5B%5D=team-name&team-name-label=Team+Name&team-name=team_selected&update-recipient=yes&participate=yes&fields%5B%5D=name&name-label=Team+Captain&name=ftergs&fields%5B%5D=team-captain-lol-summoner-name&team-captain-lol-summoner-name-label=Team+Captain+LOL+Summoner+Name&team-captain-lol-summoner-name=asdfsda&fields%5B%5D=email&email-label=Team+Captain+Email+Address&email=asdwv%40hotmail.com&fields%5B%5D=team-captain-phone&team-captain-phone-label=Team+Captain+Phone&team-captain-phone=nasljkasdl&fields%5B%5D=teammate-one-lol-summoner-name&teammate-one-lol-summoner-name-label=Teammate+One+LOL+Summoner+Name&teammate-one-lol-summoner-name=lk&fields%5B%5D=teammate-one-email-address&teammate-one-email-address-label=Teammate+One+Email+Address&teammate-one-email-address=qlk%40hotmail.com&fields%5B%5D=teammate-two-lol-summoner-name&teammate-two-lol-summoner-name-label=Teammate+Two+LOL+Summoner+Name&teammate-two-lol-summoner-name=wlk&fields%5B%5D=teammate-two-email-address&teammate-two-email-address-label=Teammate+Two+Email+Address&teammate-two-email-address=lksdflksdlk%40hotmail.com&fields%5B%5D=teammate-three-lol-summoner-name&teammate-three-lol-summoner-name-label=Teammate+Three+LOL+Summoner+Name&teammate-three-lol-summoner-name=lklkn&fields%5B%5D=teammate-three-email-address&teammate-three-email-address-label=Teammate+Three+Email+Address&teammate-three-email-address=ljh%40hotmail.com&fields%5B%5D=teammate-four-lol-summoner-name&teammate-four-lol-summoner-name-label=Teammate+Four+LOL+Summoner+Name&teammate-four-lol-summoner-name=ljh&fields%5B%5D=teammate-four-email-address&teammate-four-email-address-label=Teammate+Four+Email+Address&teammate-four-email-address=ljkh%40hotmail.com&fields%5B%5D=alternate-one-summoner-name&alternate-one-summoner-name-label=Alternate+One+Summoner+Name&alternate-one-summoner-name=l%3Bjkh&fields%5B%5D=alternate-one-email-address&alternate-one-email-address-label=Alternate+One+Email+Address&alternate-one-email-address=ljh%40hotmail.com&fields%5B%5D=alternate-two-summoner-name&alternate-two-summoner-name-label=Alternate+Two+Summoner+Name&alternate-two-summoner-name=l%3Bjkh&fields%5B%5D=alternate-two-email-address&alternate-two-email-address-label=Alternate+Two+Email+Address&alternate-two-email-address=lkn%40hotmail.com&fields%5B%5D=alternate-three-summoner-name&alternate-three-summoner-name-label=Alternate+Three+Summoner+Name&alternate-three-summoner-name=.%2Cmn&fields%5B%5D=alternate-three-email-address&alternate-three-email-address-label=Alternate+Three+Email+Address&alternate-three-email-address=ou%40hotmail.com
 *
 */