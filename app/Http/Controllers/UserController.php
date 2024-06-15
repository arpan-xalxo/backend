<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

    return response()->json($user);

    }
}

