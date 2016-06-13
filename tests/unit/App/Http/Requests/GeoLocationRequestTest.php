<?php
/**
 * GeoLocationRequestTest
 *
 * Created 5/27/16 8:01 AM
 * Unit tests for GeoLocationRequest class
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Tests\Unit\App\Http\Requests
 */

namespace App\Http\Requests;


class GeoLocationRequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_is_authorized()
    {
        $geoLocationRequest = new \App\Http\Requests\GeoLocationRequest();
        $this->assertTrue($geoLocationRequest->authorize());
    }

    /**
     * @test
     */
    public function it_has_the_correct_input_rules()
    {
        $geoLocationRequest = new \App\Http\Requests\GeoLocationRequest();

        // check geo_lat field exists and the rules are correct
        $this->assertArrayHasKey('geo_lat', $geoLocationRequest->rules());
        $this->assertSame($geoLocationRequest->rules()['geo_lat'], 'required|float');

        // check geo_long field exists and the rules are correct
        $this->assertArrayHasKey('geo_long', $geoLocationRequest->rules());
        $this->assertSame($geoLocationRequest->rules()['geo_long'], 'required_with:geo_lat|float');
    }

    /**
     * @test
     */
    public function it_has_messages()
    {
        $geoLocationRequest = new \App\Http\Requests\GeoLocationRequest();
        $this->assertSame([
            'geo_lat.regex' => "Geo location latitude should be a float value",
            'geo_long.regex' => "Geo location longitude should be a float value",
        ], $geoLocationRequest->messages());

    }
}
