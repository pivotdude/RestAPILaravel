<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        $messages = [
            'required'    => 'Поле :attribute обязательно к заполнению.',
            'email'    => 'Поле :attribute содержит не валидный email',
            'min'      => 'Поле :attribute должно иметь минимум :min символов',
        ];
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], $messages);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['success' => 'false', 'message' => $errors], 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['success' => 'false', 'message' => 'Некорректные данные'], 401);
        }
        return $this->createNewToken($token);
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $messages = [
            'required'    => 'Поле :attribute обязательно к заполнению.',
            'email'    => 'Поле :attribute содержит не валидный email',
            'min'      => 'Поле :attribute должно иметь минимум :min символов',
            'max'      => 'Поле :attribute должно иметь максимум :max символов',
            'between' => 'Поле :attribute должно содержать от :min до :max симолов',
            'unique' => 'Аккаунт по этой почте уже зарегестрирован',
            'confirmed' => 'Поле :attribute не подтверждено'
        ];
        $validator = Validator::make($request->all(), [
            'lastName' => 'required|string|between:2,100',
            'firstName' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            // password have 'regex:/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            'password' => 'required|string|min:6', // confirmed - подтврждение?
        ], $messages);
        if($validator->fails()){
            return response()->json(['success' => false, 'message' => $validator->errors()], 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'success' => true
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoutAccount(Request $request) {
        // Изменить сообщение об ошибке выхода
//        $bodyContent = $request->getContent();
//        $bodyContent = json_decode($bodyContent, true);
//        $token = $bodyContent['Authorization'];
        // Здесь уничтожение токена
        auth()->logout();
        return response()->json(['success' => true]);

        }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'success' => true,

            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
