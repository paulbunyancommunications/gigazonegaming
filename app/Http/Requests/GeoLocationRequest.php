<?php

namespace App\Http\Requests;

use App\Http\Requests\Request as BaseRequest;

class GeoLocationRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'geo_lat' => 'required|float',
            'geo_long' => 'required_with:geo_lat|float'
        ];
    }

    public function messages()
    {
        return [
            'geo_lat.regex' => "Geo location latitude should be a float value",
            'geo_long.regex' => "Geo location longitude should be a float value",
        ];
    }
}
