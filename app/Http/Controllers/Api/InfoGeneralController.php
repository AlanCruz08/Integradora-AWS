<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InfoGeneral;
use Symfony\Component\HttpFoundation\Response;

class InfoGeneralController extends Controller
{
    public function index()
    {
        $infogeneral = InfoGeneral::all();
        return response()->json($infogeneral, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
       $infogeneral = new InfoGeneral();

       $infogeneral->tipo = $request->tipo;
       $infogeneral->numsensor = $request->numsensor;
       $infogeneral->Unidades = $request->Unidades;
       $infogeneral->SKU = $request->SKU;
       
       $infogeneral->save();

       return response()->json($infogeneral, Response::HTTP_CREATED);
    }

    public function update(Request $request, InfoGeneral $infogeneral)
    {
        $infogeneral=InfoGeneral::findOrfail($infogeneral->id);

        $infogeneral->tipo = $request->tipo;
        $infogeneral->numsensor = $request->numsensor;
        $infogeneral->Unidades = $request->Unidades;
        $infogeneral->SKU = $request->SKU;
        
        $infogeneral->save();

        return response()->json($infogeneral, Response::HTTP_OK);
    }

    public function destroy(InfoGeneral $infogeneral)
    {
        $infogeneral->delete();

        return response()->json(null, Response::HTTP_OK);
    }
}
