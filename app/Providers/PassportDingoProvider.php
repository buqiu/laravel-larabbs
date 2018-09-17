<?php

namespace App\Providers;

use Dingo\Api\Auth\Provider\Authorization;
use Dingo\Api\Routing\Route;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class PassportDingoProvider extends Authorization
{
    protected $auth;

    protected $guard = 'api';

    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth->guard($this->guard);
    }

    /**
     * Get the providers authorization method.
     *
     * @return string
     */
    public function getAuthorizationMethod()
    {
        return 'Bearer';
    }

    /**
     * Authenticate the request and return the authenticated user instance.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Dingo\Api\Routing\Route $route
     *
     * @return mixed
     */
    public function authenticate(Request $request, Route $route)
    {
        if (!$user = $this->auth->user()){
            throw new UnauthorizedHttpException(
                get_class($this),
                'Unable to authenticate with invalid API key and token.'
            );
        }
        return $user;
    }
}
