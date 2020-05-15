<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;

    protected $maxAttempts = 3;

    /**
     * Where to redirect users after login.
     *
     * @return string
     */
    protected function redirectTo()
    {
        return RouteServiceProvider::DASHBOARD;
    }



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticate(Request $request)
    {
        if ($request->exists('code')) {
            $credentials = $request->only('code');
        } else {
            $credentials = $request->only('email', 'password');
        }

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect(RouteServiceProvider::DASHBOARD);
        }
    }
    public function showTfaForm()
    {
        if (!session()->exists('token')) {
            return redirect('login');
        }
        return view('auth.tfaLogin');
    }

    /**
     * Get the failed login response instance.
     *
     * @param Request $request
     * @return Response
     *
     * @throws ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        if ($request->exists('code')) {
            throw ValidationException::withMessages([
                'code' => ['Codice non valido'],
            ]);
        } else {
            throw ValidationException::withMessages([
                $this->username() => ['Opssssss qualcosa è andato storto! 👀'/*trans('auth.failed')*/],
            ]);
        }
    }

    protected function validateLogin(Request $request)
    {
        if ($request->exists('code')) {
            $request->validate([
               'code' => 'required|string'
            ]);
        } else {
            $request->validate([
                $this->username() => 'required|string',
                'password' => 'required|string',
            ]);
        }
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if ($request->exists('code')) {
            return $request->only('code');
        } else {
            return $request->only($this->username(), 'password');
        }
    }

    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     * @return Response
     *
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.

        if (method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts($request)) {
            if (session()->exists('token')) {
                session()->flush();
                return redirect('login')->withErrors([
                    $this->username() => 'Riprova, sarai più fortunato!'
                ]);
            }

            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
