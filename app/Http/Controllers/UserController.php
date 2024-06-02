<?php
    
namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
    
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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = User::orderBy('id','DESC')->paginate(5);
        $roles = Role::pluck('name','name')->all();

        return view('users.index',compact('data','roles'))->with('i', ($request->input('page', 1) - 1) * 5);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('users.create',compact('roles'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
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
        
        $allUsers = User::orderBy('id','DESC')->get();

        $data = View::make('users.user_table_body', ['allUsers' => $allUsers])->render();
        
        $response = [
            'type'   => 'success',
            'status' => 200,
            'message' => 'User created successfully',
            'data' => $data
        ];

        return response()->json($response);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        
        $userRole = $user->roles->pluck('name','name')->all();
    
        $response = [
            'type'   => 'success',
            'status' => 200,
            'user' => $user,
            'userRole' => $userRole
        ];
        return response()->json($response);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $userRole = $user->roles->pluck('name','name')->all();
    
        $response = [
            'type'   => 'success',
            'status' => 200,
            'user' => $user,
            'userRole' => $userRole
        ];
        return response()->json($response);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {   
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
    
        $allUsers = User::orderBy('id','DESC')->get();

        $data = View::make('users.user_table_body', ['allUsers' => $allUsers])->render();
        
        $response = [
            'type'   => 'success',
            'status' => 200,
            'message' => 'User created successfully',
            'data' => $data
        ];

        return response()->json($response);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        $response = [
            'type'   => 'success',
            'status' => 200,
            'message' => 'User deleted successfully'
        ];
        return response()->json($response);
    }
}