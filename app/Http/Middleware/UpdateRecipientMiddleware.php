<?php

namespace App\Http\Middleware;

use App\Models\UpdateRecipients;
use Closure;

/**
 * Class RequestTokenMiddleware
 * @package App\Http\Middleware
 */
class UpdateRecipientMiddleware
{

    protected $rules = ['email' => 'required|email|unique:update_recipients,email', 'update-recipient' => 'required|in:yes'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        return $next($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response $response
     */
    public function terminate($request, $response)
    {
        // check for base rules, if pass then setup the insert of a new update recipient
        $validator = \Validator::make($request->all(), $this->rules, []);
        if (!$validator->fails()) {
            $updates = new UpdateRecipients();
            $updates->email = $request->input('email');

            // check if geo_lat and geo_long exist in the request
            if (!\Validator::make(
                $request->all(),
                [
                    'geo_lat' => 'required|regex:/^\d*(\.\d{2})?$/',
                    'geo_long' => 'required_with:geo_lat|regex:/^\d*(\.\d{2})?$/'
                ]
            )->fails()
            ) {
                $updates->geo_lat = $request->input('geo_lat');
                $updates->geo_long = $request->input('geo_long');
            }

            // check if the participate flag exists in the request
            if (!\Validator::make($request->all(), ['participate' => 'required_with:email|in:yes'])->fails()) {
                $updates->participate = true;
            }
            $updates->save();
        }
    }
}
