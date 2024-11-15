<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Society;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class PostController extends Controller
{
    public function index()
    {
        $society = Society::latest()->paginate(5);
        return new PostResource(true, 'List data post', $society);
    }

    public function store(Request $request)
    {
        \Log::info('Store method called', [
            'request' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        $validator = Validator::make($request->all(), [
            'id_card_number' => 'required|string',
            'password' => 'required|string',
            'name' => 'required|string',
            'born_date' => 'required|string',
            'gender' => 'required|enum',
            'address' => 'required|string',
            'token' => 'required|string',
            'regional' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'validation error',
                'data' => $validator->errors()
            ], 422);
        }

        try {
            $society = Society::create([
                'id_card_number' => $request->id_card_number,
                'name' => $request->name,
                'born_date' => $request->born_date,
                'gender' => $request->gender,
                'address' => $request->address,
                'token' => $request->token,
                'regional' => $request->regional,
            ]);
            return new PostResource(true, 'Data post berhasil ditambahkan', $society);
        } catch (\Exception $e) {
            \Log::error('Error in store method: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $society = Society::find($id);
        return new PostResource(true, 'Detail data', $society);
    }

    public function update(Request $request, Society $society)
    {
        \log::info('update method', [
            'request' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'born_date' => 'required|string',
            'gender' => 'required|string',
            'address' => 'required|string',
            'token' => 'required|string',
            'regional' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'validation error',
                'data' => $validator->errors()
            ], 422);
        }

        try {
            $society->update([
                'name' => $request->name,
                'born_date' => $request->born_date,
                'gender' => $request->gender,
                'address' => $request->address,
                'token' => $request->token,
                'regional' => $request->regional,
            ]);

            return new PostResource(true, 'Data post berhasil diperbarui', $society);
        } catch (\Exception $e) {
            \Log::error('Error Update' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Society $society)
    {
        try {
            $society->delete();
            return new PostResource(true, 'Data telah terhapus', null);
        } catch (\Exception $e) {
            \Log::error('Hapus Error' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error' . $e->getMessage(),
            ], 500);
        }
    }
}
