<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Services\Api\UserService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use JWTException;
use Validator;
use App\Services\Api\Contracts\UserServiceInterface;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\UserLoginRequest;
use App\Http\Requests\Api\UserRegisterRequest;

class UsersController extends BaseController
{
    protected $service;
    private $jwtAuth;

    public function __construct(UserServiceInterface $service, JWTAuth $jwtAuth)
    {
        parent::__construct();
        $this->jwtAuth = $jwtAuth;
        $this->service = $service;
    }

    public function register(UserRegisterRequest $request)
    {
        $inputs = $request->only('username', 'email', 'password', 'password_confirmation');
        $user = $this->service->create([
                'username' => strtolower($inputs['username']),
                'email' => strtolower($inputs['email']),
                'password' => $inputs['password'],
            ]);

        return $this->responseSuccess([
            'message' => 'Register Successfully',
        ]);
    }

    public function login(UserLoginRequest $request)
    {
        $field = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentialsRequest = $request->only('email', 'password', 'grecaptcha');

        $credentials = [
            $field => strtolower($credentialsRequest['email']),
            'password' => $credentialsRequest['password'],
        ];
        $user = User::where($field, $credentials[$field])->first();

        $credentials['is_active'] = 1;

        try {
            if (!$token = $this->jwtAuth->attempt($credentials)) {
                return response()->json(['error' => 'Please input correct email/username or password'], 401);
            }
            $user = $this->jwtAuth->authenticate($token);
        } catch (JWTException $e) {
            return response()->json(['success' => false, 'error' => 'Failed to login, please try again.'], 500);
        }

        return response()->json(compact('token'));
    }

    public function refresh()
    {
        $token = $this->jwtAuth->getToken();
        $token = $this->jwtAuth->refresh($token);
        return response()->json(compact('token'));
    }

    public function updateProfile(Request $request)
    {
        $inputs = $request->only('name');

        $updated = $this->service->update(auth()->user(), $inputs);
        if ($updated) {
            return $this->responseSuccess([]);
        }

        return $this->responseErrors(config('code.basic.save_failed'), trans('messages.validate.save_failed'));
    }

    public function getCurrentUser(Request $request)
    {
        $user = auth()->user();
        if ($user) {
            return $this->responseSuccess(compact('user'));
        }

        return $this->responseErrors(config('code.user.user_not_found'), trans('messages.user.user_not_found'));
    }

    public function logout(Request $request)
    {
        $this->jwtAuth->invalidate($this->jwtAuth->getToken());

        return $this->responseSuccess([
            'message' => trans('auth.logout.login_success'),
        ]);
    }
}
    