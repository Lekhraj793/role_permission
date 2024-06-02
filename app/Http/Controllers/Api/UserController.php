<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Expectation;
use Hash;
use View;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:user-list', ['only' => ['index']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $posts = User::all();

        return sendResponse(UserResource::collection($posts), 'Posts retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request)
    {
        try {
            $importFilePath = null;
            $originalFileName = null;
            if ($request->file('profile_photo')) {

                $filePath = '/user_images/' . date('Y');

                if (!Storage::exists($filePath)) {
                    Storage::makeDirectory($filePath, 0777, true); //creates directory
                }

                $file = $request->file('profile_photo');

                $importFilePath = Storage::disk('local')->putFile($filePath, $file);

                $originalFileName = $request->profile_photo->getClientOriginalName();
            }

            $user = User::create([
                "name" => $request->name ?? null,
                "email" => $request->email ?? null,
                "password" => Hash::make($request->password) ?? null,
                'country_code' => $request->country_code ?? null,
                "phone" => $request->phone ?? null,
                "roles" => $request->roles ?? null,
                "description" => $request->description ?? null,
                'profile_photo' => $importFilePath
            ]);
        
            // $user = User::create($input);
            $user->assignRole($request->input('roles'));

            $success = new UserResource($user);
            $message = 'User been successfully created.';
        } catch (Exception $e) {
            $success = [];
            $message = 'Oops! Unable to create a new user.';
        }

        return sendResponse($success, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $post = User::find($id);

        if (is_null($post)) return sendError('Post not found.');

        return sendResponse(new UserResource($post), 'User retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Post    $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, $id)
    {
        try {
            $importFilePath = null;
            $originalFileName = null;
            if ($request->file('profile_photo')) {

                $filePath = '/user_images/' . date('Y');

                if (!Storage::exists($filePath)) {
                    Storage::makeDirectory($filePath, 0777, true); //creates directory
                }

                $file = $request->file('profile_photo');

                $importFilePath = Storage::disk('local')->putFile($filePath, $file);

                $originalFileName = $request->profile_photo->getClientOriginalName();
            }

            $user = User::find($id);
            $oldPassword = $user->password;
            $user->update([
                "name" => $request->name ?? null,
                "email" => $request->email ?? null,
                "password" => !empty($request->password) ? Hash::make($request->password) : $oldPassword,
                'country_code' => $request->country_code ?? null,
                "phone" => $request->phone ?? null,
                "roles" => $request->roles ?? null,
                "description" => $request->description ?? null,
                'profile_photo' => $importFilePath
            ]);

            DB::table('model_has_roles')->where('model_id',$id)->delete();
        
            $user->assignRole($request->input('roles'));

            $success = new UserResource($user);
            $message = 'Yay! User has been successfully updated.';
        } catch (Exception $e) {
            $success = [];
            $message = 'Oops, Failed to update the user.';
        }

        return sendResponse($success, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            User::where('id', $id)->delete();
            return sendResponse([], 'The user has been successfully deleted.');
        } catch (Exception $e) {
            return sendError('Oops! Unable to delete user.');
        }
    }
}