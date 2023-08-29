<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function onLogin (): JsonResponse {
        return response() -> json(['status' => 'Logged In'], 200);
    }
}
