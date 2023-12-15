<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmacionMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    private $key, $api, $client;
    protected $reglasLogin = [
        'email' => 'required | string | max:60',
        'password' => 'required | string | max:60',
    ];

    protected $reglasRegister = [
        'name' => 'required | string | max:60',
        'email' => 'required | string | max:60',
        'password' => 'required | string | max:60',
    ];

    protected $reglasValidate = [
        'id' => 'required | numeric'
    ];

    protected $reglasCorreo = [
        'email' => 'required | email'
    ];

    protected $reglasVerificacion = [
        'name' => 'required | string | max:60',
        'email' => 'required | email',
        'password' => 'required | string | max:60',
        'codigo' => 'required | numeric',

    ];

    public function __construct()
    {
        $this->key = env('DB_KEY');
        $this->api = env('DB_END');

        $this->client = new Client([
            'base_uri' => $this->api,
            'headers' => [
                'Content-Type' => 'application/json',
                'api-key' => $this->key,
            ],
            'verify' => false,
        ]);
    }

    public function login(Request $request)
    {
        $validacion = Validator::make($request->all(), $this->reglasLogin);

        if ($validacion->fails())
            return response()->json([
                'msg' => 'Error en las validaciones',
                'data' => $validacion->errors(),
                'status' => '422'
            ], 422);

        //verificar si el usuario existe
        $response = $this->client->post('/app/data-erstl/endpoint/userExisting', [
            'headers' => [
                'Content-Type' => 'application/json',
                'api-key' => $this->key,
            ],
            'json' => ['email_user' => $request->email],
            'verify' => false,
        ]);
        
        $bodyResponse = $response->getBody()->getContents();
        $jsonResponse = json_decode($bodyResponse, true);
        
        if ($jsonResponse['value'] == false)
            return response()->json([
                'msg' => 'El usuario no existe',
                'data' => $request->email,
                'status' => 404
            ], 404);
        
        //verificar si la contraseña es correcta
        $response = $this->client->post('/app/data-erstl/endpoint/dataUser', [
            'headers' => [
                'Content-Type' => 'application/json',
                'api-key'=> $this->key,
            ],
            'json' => ['email_user' => $request->email],
            'verify' => false,
        ]);

        $bodyResponse = $response->getBody()->getContents();
        $jsonResponse = json_decode($bodyResponse, true);
        $haspass = $jsonResponse['user']['password'];

        $userProvidedPassword = $request->password;
        if (!Hash::check($userProvidedPassword, $haspass))
            return response()->json([
                'msg' => 'Contraseña incorrecta',
                'data' => null,
                'status' => 404
            ], 404);

        //crear el token de sesion
        $token = $this->generarToken();
        $data = [
            'email_user' => $request->email,
            'token' => $token
        ];
        $response = $this->client->post('/app/data-erstl/endpoint/saveToken', [
            'headers' => [
                'Content-Type' => 'application/json',
                'api-key'=> $this->key,
            ],
            'json' => $data,
            'verify' => false,
        ]);
        $bodyResponse = $response->getBody()->getContents();
        $jsonResponse = json_decode($bodyResponse, true);
        $statusCode = $response->getStatusCode();
        
        if ($statusCode != 201 || $statusCode != 200)
            return response()->json([
                'msg' => 'Error al guardar el token',
                'data' => null,
                'status' => 404
            ], 404);
        
        return response()->json([
            'msg' => 'Sesion iniciada',
            'data' => $token,
            'status' => 200
        ], 200);
    }
    public function generarToken()
    {
        $token = tap(new PersonalAccessToken, function ($token) {
            $token->forceFill([
                'name' => 'API Token',
                'token' => hash('sha256', Str::random(40)),
                'abilities' => ['*'],
            ])->save();
        });

        return $token->plainTextToken;
    }

    public function register(Request $request)
    {
        $validacion = Validator::make($request->all(), $this->reglasRegister);

        if ($validacion->fails())
            return response()->json([
                'msg' => 'Error en las validaciones',
                'data' => $validacion->errors(),
                'status' => '422'
            ], 422);

        try {
            $response = $this->client->post('/app/data-erstl/endpoint/userExisting', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'api-key' => $this->key,
                ],
                'json' => ['email_user' => $request->email],
                'verify' => false,
            ]);
            
            $bodyResponse = $response->getBody()->getContents();
            $jsonResponse = json_decode($bodyResponse, true);
            
            if ($jsonResponse['value'] == true)
                return response()->json([
                    'msg' => 'El usuario ya existe',
                    'data' => $request->email,
                    'status' => 200
                ], 200);

            $hashPassword = Hash::make($request->password);
            $data = [
                'name_user' => $request->name,
                'email_user' => $request->email,
                'password_user' => $hashPassword
            ];
            
            $response = $this->client->post('/app/data-erstl/endpoint/registro', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'api-key' => $this->key,
                ],
                'json' => $data,
                'verify' => false,
            ]);
            
            $statusCode = $response->getStatusCode();
            $data = $response->getBody()->getContents();
            $jsonResponse = json_decode($data, true);

            if ($statusCode != 201)
                return response()->json([
                    'msg' => 'Error al registrar',
                    'data' => $jsonResponse,
                    'status' => $statusCode
                ], $statusCode);
            
            return response()->json([
                'msg' => 'Usuario registrado',
                'data' => $jsonResponse,
                'status' => $statusCode
            ], $statusCode);

        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error al recuperar registros!',
                'error' => $e->getMessage(),
                'status' => $e->getCode()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'msg' => 'Sesión cerrada',
            'status' => 'success'
        ], 200);
    }

    public function validar(Request $request)
    {
        $accessToken = $request->bearerToken();

        if (!$accessToken) {
            return response()->json([
                'msg' => 'Token no enviado',
                'data' => null,
                'status' => 404
            ], 404);
        }

        $id = $request->id;
        $token = PersonalAccessToken::findToken($accessToken);

        if (!$token || $token->revoked) {
            return response()->json([
                'msg' => 'token no encontrado o revocado',
                'data' => false,
                'status' => 401
            ], 401);
        }

        $consu = DB::table('personal_access_tokens')
            ->where('tokenable_id', $id)
            ->where('token', $token)
            ->first();

        if (!$consu)
            response()->json([
                'msg' => 'El token no es valido',
                'data' => false,
                'status' => 422
            ], 422);

        return response()->json([
            'msg' => 'Token valido',
            'data' => true,
            'status' => 200
        ], 200);
    }

    public function enviarCorreo(string $email)
    {
        $emailExist = DB::table('verify_email')->where('email', $email)->first();
        if ($emailExist)
            DB::table('verify_email')->where('email', $email)->delete();

        $number = rand(1000, 9999);

        DB::table('verify_email')->insert([
            'codigo' => $number,
            'email' => $email
        ]);

        Mail::to($email)->send(new ConfirmacionMail($number));

        return response()->json([
            'msg' => 'Correo Enviado',
            'data' => $email,
            'status' => 201
        ], 201);
    }

    public function verificacion(Request $request)
    {
        $validacion = Validator::make($request->all(), $this->reglasVerificacion);

        if ($validacion->fails())
            return response()->json([
                'msg' => 'Error en las Validaciones',
                'data' => $validacion->errors(),
                'status' => '422'
            ], 422);

        $codigo = $request->codigo;
        $email = $request->email;
        $relation = DB::table('verify_email')->where('email', $email)->where('codigo', $codigo)->first();

        if (!$relation)
            return response()->json([
                'msg' => 'Codigo no Valido',
                'data' => null,
                'status' => 404
            ], 404);

        $verify = DB::table('verify_email')->where('email', $email)->where('codigo', $codigo)->update(['verificado' => true]);

        if (!$verify)
            return response()->json([
                'msg' => 'Error al Verificar',
                'data' => null,
                'status' => 404
            ], 404);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }
}
