<?php

namespace App\Http\Middleware;

use App\Http\Requests\UpdateRecipientRequest;
use Closure;
use App\Http\Requests\GeoLocationRequest;
use App\Models\UpdateRecipients;
use Illuminate\Support\Facades\Validator;

/**
 * Class RequestTokenMiddleware
 * @package App\Http\Middleware
 */
class UpdateRecipientMiddleware
{

    /**
     * Handle an incoming request and add email to update recipients if validation passes.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $updateRequest = new UpdateRecipientRequest();
        // check for base rules, if pass then setup the insert of a new update recipient
        $validator = Validator::make($request->all(), $updateRequest->rules(), []);
        if (!$validator->fails()) {
            $updates = new UpdateRecipients();
            $updates->email = $request->input('email');

            // check if geo_lat and geo_long exist in the request
            $geoLocations = array_map(function ($e) {
                return floatval($e);
            }, $request->all());
            $geoValidation = new GeoLocationRequest();
            $geoValidator = Validator::make(
                $geoLocations,
                $geoValidation->rules(),
                $geoValidation->messages()
            );

            if (!$geoValidator->fails()
            ) {
                $updates->geo_lat = $request->input('geo_lat');
                $updates->geo_long = $request->input('geo_long');
            }

            // check if the participate flag exists in the request
            if (!Validator::make($request->all(), ['participate' => 'required_with:email|in:yes'])->fails()) {
                $updates->participate = true;
            }
            $updates->save();
        }
        return $next($request);
    }
}
