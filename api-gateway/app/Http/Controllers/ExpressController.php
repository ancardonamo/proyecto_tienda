<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExpressController extends Controller
{
    private $baseUrl = 'http://127.0.0.1:3000';
    private $token = 'Token miclave123'; // Requerido por tu middleware de Express

    public function index()
    {
        $response = Http::withHeaders(['Authorization' => $this->token])->get("{$this->baseUrl}/users");
        return response()->json($response->json(), $response->status());
    }

    public function store(Request $request)
    {
        $response = Http::withHeaders(['Authorization' => $this->token])->post("{$this->baseUrl}/users", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function show($id)
    {
        $response = Http::get("{$this->baseUrl}/users/{$id}");
        return response()->json($response->json(), $response->status());
    }

    public function update(Request $request, $id)
    {
        $response = Http::put("{$this->baseUrl}/users/{$id}", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function destroy($id)
    {
        $response = Http::delete("{$this->baseUrl}/users/{$id}");
        return response()->json($response->json(), $response->status());
    }
}