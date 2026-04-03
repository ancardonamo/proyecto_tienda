<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FlaskController extends Controller
{
    private $baseUrl = 'http://127.0.0.1:5000/api'; // Flask Pedidos

    public function index()
    {
        $response = Http::get("{$this->baseUrl}/pedidos");
        return response()->json($response->json(), $response->status());
    }

    public function store(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/pedidos", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function show($id)
    {
        $response = Http::get("{$this->baseUrl}/pedidos/{$id}");
        return response()->json($response->json(), $response->status());
    }

    public function update(Request $request, $id)
    {
        $response = Http::put("{$this->baseUrl}/pedidos/{$id}", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function destroy($id)
    {
        $response = Http::delete("{$this->baseUrl}/pedidos/{$id}");
        return response()->json($response->json(), $response->status());
    }
}