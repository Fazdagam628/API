<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Society;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_card_number' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $society = Society::where('id_card_number', $request->id_card_number)->first();

        if (!$society || !Hash::check($request->password, $society->password)) {
            return response()->json([
                'message' => 'ID Card Number or Password incorrect'
            ], 401);
        }

        //generate token using md5 of id card number
        $token = md5($society->id_card_number);
        $society->token = $token;
        $society->save();

        $response = [
            'name' => $society->name,
            'born_date' => $society->born_date,
            'gender' => $society->gender,
            'address' => $society->address,
            'token' => $society->token,
            'regional' => $society->regional,
        ];

        return response()->json($response, 200);
    }

    public function logout(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return response()->json([
                'message' => 'Invalid token'
            ], 401);
        }

        $society = Society::where('token', $token)->first();

        if (!$society) {
            return response()->json([
                'message' => 'Invalid token'
            ], 401);
        }

        $society->token = null;
        $society->save();

        return response()->json([
            'message' => 'Logout success'
        ], 200);
    }
}
