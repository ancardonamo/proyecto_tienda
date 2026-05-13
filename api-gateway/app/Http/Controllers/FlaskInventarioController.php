<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FlaskInventarioController extends Controller
{
    private $baseUrl = 'http://inventario-flask:5001/api'; // Flask Inventario en puerto 5001

    public function index()
    {
        $response = Http::get("{$this->baseUrl}/inventario");
        return response()->json($response->json(), $response->status());
    }

    public function store(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/inventario", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function show($id)
    {
        $response = Http::get("{$this->baseUrl}/inventario/{$id}");
        return response()->json($response->json(), $response->status());
    }

    public function update(Request $request, $id)
    {
        $response = Http::put("{$this->baseUrl}/inventario/{$id}", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function destroy($id)
    {
        $response = Http::delete("{$this->baseUrl}/inventario/{$id}");
        return response()->json($response->json(), $response->status());
    }
}