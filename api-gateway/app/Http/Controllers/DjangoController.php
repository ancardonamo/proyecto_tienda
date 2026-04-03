<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DjangoController extends Controller
{
    private $baseUrl = 'http://127.0.0.1:8000/api'; // Puerto por defecto de Django

    public function index()
    {
        $response = Http::get("{$this->baseUrl}/productos/");
        return response()->json($response->json(), $response->status());
    }

    public function store(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/productos/", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function show($id)
    {
        $response = Http::get("{$this->baseUrl}/productos/{$id}/");
        return response()->json($response->json(), $response->status());
    }

    public function update(Request $request, $id)
    {
        $response = Http::put("{$this->baseUrl}/productos/{$id}/", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function destroy($id)
    {
        $response = Http::delete("{$this->baseUrl}/productos/{$id}/");
        return response()->json($response->json(), $response->status());
    }
}