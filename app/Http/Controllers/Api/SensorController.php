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
        'fecha' => 'required|date_format:Y-m-d H:i:s',
    ];

    private $key, $api, $client;

    public function __construct()
    {
        $this->key = env('DB_KEY');
        $this->api = env('DB_END');

        $this->client = Http::withHeaders([
            'api-key' => $this->key,
            'Content-Type' => 'application/json',
        ])->baseUrl($this->api)->withoutVerifying();
    }

    public function datos(){
        try {
            $response = $this->client->get('/app/data-erstl/endpoint/alldata');
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
    public function index()
    {
        $sensores = Sensores::all();
        return response()->json($sensores, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $sensores = new Sensores();

        $sensores->N_sensor = $request->N_sensor;
        $sensores->Valor = $request->Valor;
        $sensores->Descripcion = $request->Descripcion;
        
        $sensores->save();

        return response()->json($sensores, Response::HTTP_CREATED);
    }

    public function update(Request $request, Sensores $sensores)
    {
        $sensores=Sensores::findOrfail($sensores->id);

        $sensores->N_sensor = $request->N_sensor;
        $sensores->Valor = $request->Valor;
        $sensores->Descripcion = $request->Descripcion;
        
        $sensores->save();

        return response()->json($sensores, Response::HTTP_OK);

    }

    public function destroy(Sensores $sensores)
    {
        $sensores->delete();

        return response()->json(null, Response::HTTP_OK);

    }
    


    public function carga(Request $request)
    {
        $validacion = Validator::make($request->all(), $this->reglasSensores);
    
        if ($validacion->fails()) {
            return response()->json([
                'msg' => 'Error en las validaciones',
                'data' => $validacion->errors(),
                'status' => 422
            ], 422);
        }
    
        try {
            $data = [
                'tipo' => $request->tipo,
                'nSensor' => $request->nSensor,
                'valor' => $request->valor,
                'fecha' => $request->fecha,
            ];
    
            // Proceso de registro de datos en tu API o sistema
            $response = $this->client->post('/app/data-erstl/endpoint/registro-datos', [
                'json' => $data,
            ]);
    
            $statusCode = $response->status();
    
            if ($statusCode !== 201) {
                $jsonResponse = $response->json();
                return response()->json([
                    'msg' => 'Error al registrar datos',
                    'data' => $jsonResponse,
                    'status' => $statusCode
                ], $statusCode);
            }
    
            $jsonResponse = $response->json();
            return response()->json([
                'msg' => 'Datos registrados correctamente',
                'data' => $jsonResponse,
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

