<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensores;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class SensorController extends Controller
{

    private $reglasSensores = [
        'tipo' => 'required|string',
        'nSensor' => 'required|string',
        'valor' => 'required|string',
        'fecha' => 'required',
    ];

    private $key, $api, $client;

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

    public function datos(Request $request){
        try {
            $response = $this->client->post('/app/data-erstl/endpoint/alldata', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'api-key' => $this->key,
                ],
                'json' => ['email_user' => $request->email],
                'verify' => false,
            ]);
            $statusCode = $response->getStatusCode();
            $data = $response->getBody()->getContents();

            $datos = json_decode($data, true);

            return response()->json([
                'msg' => 'Datos obtenidos de la API de MongoDB',
                'data' => $datos,
                'status' => $statusCode
            ], $statusCode);
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error al obtener datos de la API de MongoDB',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
    
    public function carga(Request $request)
    {
        // $validacion = Validator::make($request->all(), $this->reglasSensores);
    
        // if ($validacion->fails()) {
        //     return response()->json([
        //         'msg' => 'Error en las validaciones',
        //         'data' => $validacion->errors(),
        //         'status' => 422
        //     ], 422);
        // }
    
        try {
            $data = json_decode($request->getContent(), true);
            $completeData = [
                "email_user" => "alansasuke0@gmail.com",
                "data" => $data,
            ];
            $response = $this->client->post('/app/data-erstl/endpoint/carga', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'api-key' => $this->key,
                ],
                'json' => $completeData,
                'verify' => false,
            ]);

            $statusCode = $response->getStatusCode();
    
            if ($statusCode !== 201) {
                $jsonResponse = $response->getBody()->getContents();
                return response()->json([
                    'msg' => 'Error al registrar datos',
                    'data' => $jsonResponse,
                    'status' => $statusCode
                ], $statusCode);
            }
    
            return response()->json([
                'msg' => 'Datos registrados correctamente',
                'data' => null,
                'status' => $statusCode
            ], $statusCode);
    
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error al procesar los datos',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}

