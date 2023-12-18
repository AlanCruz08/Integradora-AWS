<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmacionMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client;


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
            'json' => ['email_user' => $request->email],
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
            'json' => ['email_user' => $request->email]
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
        
        return response()->json([
            'msg' => 'Sesion iniciada',
            'data' => $request->email,
            'status' => 200
        ], 200);
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

        $response = $this->client->post('/app/data-erstl/endpoint/userExisting', [
            'json' => ['email_user' => $request->email],
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
            'json' => $data,
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
}
