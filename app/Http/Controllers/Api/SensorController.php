<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensores;
use Symfony\Component\HttpFoundation\Response;

class SensorController extends Controller
{

    public function datos(){
        
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


    public function cargarDatos(Request $request)
    {
        // Obtén los datos enviados desde Python
        $data = $request->json()->all();

        // guardarlos en la base de datos de mongo
        $sensores = new Sensores();
        $sensores->N_sensor = $data['N_sensor'];
        $sensores->Valor = $data['Valor'];
        $sensores->Descripcion = $data['Descripcion'];
        $sensores->save();


        // Devuelve una respuesta
        return response()->json(['message' => 'Datos recibidos correctamente en Laravel'], 200);
    }
}
