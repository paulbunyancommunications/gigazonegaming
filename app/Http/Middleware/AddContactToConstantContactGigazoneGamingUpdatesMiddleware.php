<?php

namespace App\Http\Middleware;

use App\Jobs\ConstantContactAddRecipientJob;
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
            $newConstantContactUser = (new ConstantContactAddRecipientJob(
                [
                    'apiKey' => config('constant_contact.api_key'),
                    'apiToken' => config('constant_contact.api_token'),
                    'apiSecret' => config('constant_contact.api_secret'),
                    'listName' => config('constant_contact.list_name'),
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
