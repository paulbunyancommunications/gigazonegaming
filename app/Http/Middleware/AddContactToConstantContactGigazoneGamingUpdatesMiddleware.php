<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class AddContactToConstantContactGigazoneGamingUpdatesMiddleware
 * @package App\Http\Middleware
 */
class AddContactToConstantContactGigazoneGamingUpdatesMiddleware
{
    use DispatchesJobs;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        // if update-recipient is set to yes then add the user to the queue to ber added
        if (strtolower($request->input('update-recipient')) === 'yes') {
            $newConstantContactUser = (new \App\Jobs\ConstantContactAddRecipientJob(
                [
                    'apiKey' => env('CONSTANT_CONTACT_API_KEY', 'CONSTANT_CONTACT_API_KEY'),
                    'apiSecret' => env('CONSTANT_CONTACT_API_SECRET', 'CONSTANT_CONTACT_API_SECRET'),
                    'apiToken' => env('CONSTANT_CONTACT_API_TOKEN', 'CONSTANT_CONTACT_API_TOKEN'),
                    'listName' => env('CONSTANT_CONTACT_LIST_NAME', 'Update List'),
                    'email' => $request->input('email'),
                    'name' => $request->input('name')
                ]
                // use the same config that form_mail uses
            ))->delay(config('form_mail.delay.send_message', 10));
            $this->dispatch($newConstantContactUser);
        }
        return $next($request);
    }
}
