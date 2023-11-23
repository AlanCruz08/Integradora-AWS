<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensores;
use Symfony\Component\HttpFoundation\Response;

class SensorController extends Controller
{
    public function index()
    {
        $sensores = Sensores::all();
        return response()->json($sensores, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $sensores = new Sensores();

        $sensores->numsensor = $request->numsensor;
        $sensores->valor = $request->valor;
        $sensores->descripcion = $request->descripcion;
        
        $sensores->save();

        return response()->json($sensores, Response::HTTP_CREATED);
    }

    public function update(Request $request, Sensores $sensores)
    {
        $sensores=Sensores::findOrfail($sensores->id);

        $sensores->numsensor = $request->numsensor;
        $sensores->valor = $request->valor;
        $sensores->descripcion = $request->descripcion;
        
        $sensores->save();

        return response()->json($sensores, Response::HTTP_OK);

    }

    public function destroy(Sensores $sensores)
    {
        $sensores->delete();

        return response()->json(null, Response::HTTP_OK);

    }
}
