<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Auth;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $users = User::all();
        // return UserResource::collection($users);

        $data = User::get();
        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
        $data['name'] = $request['name'];
        $data['email'] = $request['email'];
        $data['password'] = $request['password'];
        User::create($data);
        return response()->json([
            'message' => "Successfully created",
            'success' => true
        ], 200);
      }

    public function login(Request $request)
    {
        $fields= $request->validate([   
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user =User::where(['email' => $fields['email']])->first();

        if(!$user || !Hash::check($fields['password'], $user->password))
{
    return response([
        'message' => 'bad Creds'
    ], 401);
}
        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'success'=>false,
                'status'=>200,
            ]);
        }
        $user = auth()->user();
        $token = $user->createToken('my-app-token')->plainTextToken;
     
        return response([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'valid' => $user->valid,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'token' => $token
            // 'token_expires_at' => $token->token->expires_at,
        ], 200);

    }


// public function login(Request $request)
//     {
//         $this->validate($request, [
//             'email' => 'required|max:255',
//             'password' => 'required'
//         ]);

//         $login = $request->only('email', 'password');

//         if (!Auth::attempt($login)) {
//             return response(['message' => 'Invalid login credential!!'], 401);
//         }
//         /**
//          * @var User $user
//          */
//         $user = Auth::user();
//         $token = $user->createToken($user->name);

//         return response([
//             'id' => $user->id,
//             'name' => $user->name,
//             'email' => $user->email,
//             'created_at' => $user->created_at,
//             'updated_at' => $user->updated_at,
//             'token' => $token->accessToken,
//             'token_expires_at' => $token->token->expires_at,
//         ], 200);
//     }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // public function store(StoreUserRequest $request) // hedhy ki nzid l auth nhotha
    public function store(Request $request)
    {
        $users = User::create($request->all());

        return new UserResource($users);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {

return response($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->all());

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response(null, 204);
    }
}
