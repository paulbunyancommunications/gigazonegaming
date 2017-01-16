<?php
/**
 * ${CLASS_NAME}
 *
 * Created 12/21/16 10:02 AM
 * Description of this file here....
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package App\Helpers\Backend\Manage\Api
 * @subpackage Subpackage
 */

namespace App\Helpers\Backend\Manage\Api;


/**
 * Class Filter
 * @package App\Helpers\Backend\Manage\Api
 */
class Filter
{

    /**
     * @param $data
     * @param $filterFields
     * @return mixed
     */
    public static function filterSingleDimension($data, $filterFields)
    {
        // filter the return
        for ($f = 0; $f < count($filterFields); $f++) {
            if (array_key_exists($filterFields[$f], $data)) {
                unset($data[$filterFields[$f]]);
            }
        }

        return $data;
    }

    /**
     * @param $get
     * @param $filterFields
     * @param $types
     * @return mixed
     */
    public static function filterMultiDimension($get, $filterFields, $types)
    {
        // filter the return
        foreach($types as $type) {
            for ($i = 0; $i < count($get); $i++) {
                for ($f = 0; $f < count($filterFields); $f++) {
                    if (array_key_exists($type, $get[$i])
                        && array_key_exists($filterFields[$f],$get[$i][$type])
                    ) {
                        unset($get[$i][$type][$filterFields[$f]]);
                    }
                }
            }
        }

        return $get;
    }

}