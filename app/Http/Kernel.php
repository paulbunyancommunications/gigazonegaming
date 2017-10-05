<?php

namespace App\Http;

use App\Http\Middleware\AddContactToConstantContactGigazoneGamingUpdatesMiddleware;
use App\Http\Middleware\Api;
use App\Http\Middleware\Auth\SentinelAdminUser;
use App\Http\Middleware\Auth\SentinelAuthenticate;
use App\Http\Middleware\Auth\SentinelHasAccess;
use App\Http\Middleware\Auth\SentinelNotCurrentUser;
use App\Http\Middleware\Auth\SentinelRedirectAdmin;
use App\Http\Middleware\Auth\SentinelRedirectIfAuthenticated;
use App\Http\Middleware\Auth\SentinelStandardUser;
use App\Http\Middleware\Auth\ValidateRole;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\TournamentSignupMiddleware;
use App\Http\Middleware\UpdateRecipientMiddleware;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Middleware\WPAdminMiddleware;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\Authorize;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
        ],

        'api' => [
            'throttle:60,1',
            Api::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'can' => Authorize::class,
        'throttle' => ThrottleRequests::class,
        'WPAdmin' => WPAdminMiddleware::class,
        'UpdateRecipient' => UpdateRecipientMiddleware::class,
        'CCAddRecipient' => AddContactToConstantContactGigazoneGamingUpdatesMiddleware::class,

        'TournamentSignUp' => TournamentSignUpMiddleware::class,
        'auth' => SentinelAuthenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'guest' => SentinelRedirectIfAuthenticated::class,
        'standardUser' => SentinelStandardUser::class,
        'admin' => SentinelAdminUser::class,
        'hasAccess' => SentinelHasAccess::class,
        'notCurrentUser' => SentinelNotCurrentUser::class,
        'redirectAdmin' => SentinelRedirectAdmin::class,
        'validateRole' => ValidateRole::class,
    ];
}
