<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'address' => 'required|string|max:255',
            'birthday' => 'required|date',
            'role' => 'required|string',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),
            'address' => $request->input('address'),
            'birthday' => $request->input('birthday'),
        ];

        try {
            $user = User::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditambahkan',
                'data' => $user,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    function getUser()
    {
        try {
            $user = User::get();
            return response()->json([
                'status' => true,
                'message' => 'berhasil load data user',
                'data' => $user,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'gagal load data user. ' . $e->getMessage(),
            ]);
        }
    }

    function getDetailUser($id)
    {
        try {
            $user = User::where('id', $id)->first();
            return response()->json([
                'status' => true,
                'message' => 'berhasil load data detail user',
                'data' => $user,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'gagal load data detail user. ' . $e,
            ]);
        }
    }

    function update_user($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => ['required', Rule::unique('users')->ignore($id)],
            "address" => 'required',
            "birthday" => 'required',
            'role' => 'required',
            'password' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
        $data = [
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'role' => $request->get('role'),
            "address" => $request->get("address"),
            "birthday" => $request->get("birthday"),
        ];
        try {
            $update = User::where('id', $id)->update($data);
            return Response()->json([
                "status" => true,
                'message' => 'Data berhasil diupdate'
            ]);
        } catch (Exception $e) {
            return Response()->json([
                "status" => false,
                'message' => $e
            ]);
        }
    }

    function hapus_user($id)
    {
        try {
            User::where('id', $id)->delete();
            return Response()->json([
                "status" => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return Response()->json([
                "status" => false,
                'message' => 'gagal hapus user. ' . $e,
            ]);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
        $credentials = $request->only('email', 'password');
        $token = Auth::guard('api')->attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }


        $user = Auth::guard('api')->user();
        return response()->json([
                'status' => true,
                'message'=>'Sukses login',
                'data'=>$user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
    }

    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json([
            'status' => true,
            'message' => 'Sukses logout',
        ]);
    }
}
