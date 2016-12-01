<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Password\PasswordResetLoginRequest;
use App\Http\Requests\Auth\Password\PasswordResetPasswordRequest;
use App\Http\Requests\Auth\Password\PasswordSendRecoverCodeRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\Auth\Users\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\View;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Illuminate\Support\Facades\Mail;

/**
 * Class PasswordController
 * @package App\Http\Controllers\Auth
 */
class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * @var string
     */
    protected $redirectPath = "login";
    /**
     * @var string
     */
    protected $subject = "Your Password Reset Link for website.com";

    /**
     * Create a new password controller instance.
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('guest');

        return $this;
    }


    /**
     * Show recover form
     *
     * @return mixed
     */
    public function recover()
    {
        $this->context['title'] = 'Recover Login';
        return View::make('auth.recover', $this->context);

    }

    /**
     * Reset password form
     *
     * @param PasswordResetLoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(PasswordResetLoginRequest $request)
    {

        $user = Sentinel::findByCredentials(['login' => $request->get('user')]);
        $reminder = Reminder::exists($user);

        if ($user && $reminder && $reminder->code === $request->get('key')) {
            // show password reset form
            $this->context['title'] = 'Reset Password';
            $this->context['this_user'] = $user;
            $this->context['reminder'] = $reminder;
            return View::make('auth.reset', $this->context);

        } else {
            // Bad or missing key, redirect back to the homepage with error
            return redirect('auth/login')->with('error', trans('passwords.token'));
        }
    }

    /**
     * Set user password to submitted
     *
     * @param User $user
     * @param PasswordResetPasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(User $user, PasswordResetPasswordRequest $request)
    {

        if ($reminder = Reminder::complete($user, $request->input('code'), $request->input('password'))) {
            // Reminder was successful
            // redirect back to login page with message
            return redirect('auth/login')->with('success', trans('passwords.reset'));
        } else {
            // Reminder not found or not completed.
            return redirect('auth/login')->with('error', trans('passwords.token'));
        }


    }

    /**
     * Send recovery code to requester if they exist in the database
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendRecoverCode(PasswordSendRecoverCodeRequest $request)
    {
        $credentials = [
            'login' => $request->input('email'),
        ];

        $user = Sentinel::findByCredentials($credentials);
        if ($user) {
            // generate random string
            $reminder = Reminder::create($user);
            // setup context elements for the email message
            $this->context['reminder'] = $reminder;
            $this->context['title'] = 'Login Recovery';
            $this->context['this_user'] = $user;
            Mail::send('email.auth.recover', $this->context, function ($m) use ($user) {
                $m->to($user->email);
                $m->subject('Login Recovery');
                $m->from(Config::get('mail.from.address'));
            });

        }

        return redirect('/auth/login')->with('success', trans('passwords.sent'));
    }


}
