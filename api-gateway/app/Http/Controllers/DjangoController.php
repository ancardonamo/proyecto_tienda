<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DjangoController extends Controller
{
    // Cambiamos la base para que termine en /api/
    private $baseUrl = 'http://productos-django:8000/api/'; 

    public function index()
    {
        // Quitamos la barra inicial de productos porque ya está en baseUrl
        $response = Http::get("{$this->baseUrl}productos/");
        return response()->json($response->json(), $response->status());
    }

    public function store(Request $request)
    {
        $response = Http::post("{$this->baseUrl}productos/", $request->all());
        
        // Si Django devuelve error, esto nos permitirá verlo en Thunder Client
        if ($response->failed()) {
            return response()->json([
                'error_de_django' => $response->json(),
                'status' => $response->status()
            ], $response->status());
        }

        return response()->json($response->json(), $response->status());
    }

    public function show($id)
    {
        $response = Http::get("{$this->baseUrl}productos/{$id}/");
        return response()->json($response->json(), $response->status());
    }

    public function update(Request $request, $id)
    {
        $response = Http::put("{$this->baseUrl}productos/{$id}/", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function destroy($id)
    {
        $response = Http::delete("{$this->baseUrl}productos/{$id}/");
        return response()->json($response->json(), $response->status());
    }
}