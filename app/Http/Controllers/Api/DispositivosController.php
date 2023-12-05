<?php

namespace App\Http\Controllers\Api;

use App\Models\Dispositivos;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;   


class DispositivosController extends Controller
{
    public function index()
    {
        $dispositivos = Dispositivos::all();
        return response()->json($dispositivos, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $dispositivos = new Dispositivos();
        
        $dispositivos->Nombre = $request->Nombre;
        $dispositivos->Descripcion = $request->Descripcion;

        $dispositivos->save();

        return response()->json($dispositivos, Response::HTTP_CREATED);
    }

    public function update(Request $request, Dispositivos $dispositivos)
    {
        $dispositivos=Dispositivos::findOrfail($dispositivos->id);

        $dispositivos->Nombre = $request->Nombre;
        $dispositivos->Descripcion = $request->Descripcion;

        $dispositivos->save();

        return response()->json($dispositivos, Response::HTTP_OK);
    }

    public function destroy(Dispositivos $dispositivos)
    {
        $dispositivos->delete();

        return response()->json(null, Response::HTTP_OK);
    }
}
