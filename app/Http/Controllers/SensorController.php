<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class SensorController extends Controller
{
    private $key, $api, $client;

    private $reglasSensores = [
        'tipo' => 'required|string',
        'nSensor' => 'required|string',
        'valor' => 'required|string',
        'fecha' => 'required',
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

    public function datos(Request $request){
        $response = $this->client->post('/app/data-erstl/endpoint/alldata', [
            'json' => ['email_user' => $request->email],
        ]);
        $statusCode = $response->getStatusCode();
        $data = $response->getBody()->getContents();
        $dataJson = json_decode($data, true);

        return response()->json([
            'msg' => 'Datos obtenidos de la API de MongoDB',
            'data' => $dataJson,
            'status' => $statusCode
        ], $statusCode);
    }
    
    public function carga(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $completeData = [
            "email_user" => "alansasuke0@gmail.com",
            "data" => $data,
        ];
        $response = $this->client->post('/app/data-erstl/endpoint/carga', [
            'json' => $completeData,
        ]);

        $statusCode = $response->getStatusCode();

        if ($statusCode !== 201) {
            return response()->json([
                'msg' => 'Error al registrar datos',
                'data' => null,
                'status' => $statusCode
            ], $statusCode);
        }

        return response()->json([
            'msg' => 'Datos registrados correctamente',
            'data' => null,
            'status' => $statusCode
        ], $statusCode);
    }
}

