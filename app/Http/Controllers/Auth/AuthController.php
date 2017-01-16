<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginFormRequest;
use App\Http\Requests\Auth\GenerateAccountFormRequest;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

/**
 * Class AuthController
 * @package App\Http\Controllers\Auth
 */
class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */


    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        return $this;
    }

    /**
     *
     */
    public function index()
    {
        if (Sentinel::guest()) {
            redirect('/auth/login');
        } else {
            redirect('/dashboard');
        }
    }

    public function create()
    {
        $this->context['title'] = "Create an account";
        $this->context['layout'] = "auth";
        return View::make('auth.create', $this->context);
    }

    public function generate(GenerateAccountFormRequest $request)
    {

    }


    /**
     * Show login form
     *
     * @return mixed
     */
    public function login()
    {
        $this->context['title'] = "Login";
        $this->context['layout'] = "auth";
        return View::make('auth.login', $this->context);
    }

    /**
     * Authenticate a user submitting from the login form
     *
     * @param LoginFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(LoginFormRequest $request)
    {
        try {
            if (Sentinel::authenticate(
                [
                    'email' => $request->input('email'),
                    'password' => $request->input('password')
                ],
                Request::input('remember'))
            ) {
               return $this->redirectWhenLoggedIn();
            } else {
                return redirect()->back()->withInput()->with('error', trans('auth.failed'));
            }
        } catch (NotActivatedException $e) {
            return redirect()->back()->withInput()->with('error', trans('auth.not_activated'));
        } catch (ThrottlingException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Logout user
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        if (Sentinel::getUser()) {
            Sentinel::logout();
            Session::flash('success', 'Logged out successfully!');
        }
        return redirect('auth/login');
    }


    protected function redirectWhenLoggedIn()
    {
        return redirect('/dashboard')->with('success', trans('auth.success'));
    }



}
