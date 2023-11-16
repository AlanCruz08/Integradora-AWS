<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registro;
use Symfony\Component\HttpFoundation\Response;


class RegistroController extends Controller
{
    public function index()
    {
        $registro = Registro::all();
        return response()->json($registro, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $registro = new Registro();
        
        $registro->Sensor = $request->Sensor;
        $registro->Status = $request->Status;
        $registro->Data = $request->Data;

        $registro->save();

        return response()->json($registro, Response::HTTP_CREATED);
    }

    public function update(Request $request, Registro $registro)
    {
        $registro=Registro::findOrfail($registro->id);

        $registro->Sensor = $request->Sensor;
        $registro->Status = $request->Status;
        $registro->Data = $request->Data;

        $registro->save();

        return response()->json($registro, Response::HTTP_OK);
    }

    public function destroy(Registro $registro)
    {
        $registro->delete();

        return response()->json(null, Response::HTTP_OK);
    }
}
