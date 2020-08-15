<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Login as login;

use DataTables;
use Validator;
use Illuminate\Support\Carbon; 
use Illuminate\Support\Facades\File;

class UserloginController extends Controller
{

    function __construct()
    {
        $this->view = 'user_login';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        return view($this->view . '.user', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $render = [
            'action' => route('user_akses.store'),
            'method' => method_field('post'),
            'username' => '',
            'judul' => 'Tambah data user',
            'password' => '',
            'nama' => '',
            'email' => '',
            'foto' => '',
            'level' => '',
            'active' => '',
            'date_create' => '',
            'log' => '',
            'id_divisi' => '',
        ];
        return view('user_form', compact('render'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $required = [
            'username' => 'unique:login,username',
            'nama' => 'required',
            'email' => 'required',  
            'foto' => 'mimes:png,bmp,jpg,JPG,jpeg'
        ];
        $error = Validator::make($request->all(), $required);
        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        if ($request->file('foto')) {
            $file = $request->file('foto');
            $file_ext = $file->getClientOriginalExtension();
            $date = Carbon::now();
            $fileinsert = rand(1, 100) . $date->format('y-m-d') . '.' . $file_ext;
            $filename = $request->file('foto')->move('public/foto_user', $fileinsert);
            $dfhoto = ['foto' => $fileinsert];
        } else {
            $dfhoto = ['foto' => 'null'];
        }
        $update = [
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'name' => $request->nama,
            'email' => $request->email, 
            'active' => 'Y',
        ];
        $userUpdate = array_merge($dfhoto, $update);
        login::insert($userUpdate);
        return response()->json(['success' => 'data berhasil di simpan']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function api(Request $request)
    {
        $data = login::findOrFail($request->id);
        return response()->json($data, 200);
    }

 
    public function show($id)
    {
        $data = login::findOrFail($id);
        return view('user_detail', $data);
    }

    public function table()
    {
        $datas = login::select('id', 'username', 'name', 'email', 'foto', 'active')->get();
        return DataTables::of($datas)
            ->addColumn('action', function ($f) {
                $button = '<button type="button" name="edit" to="' . $f->id . '" class="edit btn btn-primary btn-sm">Edit</button>';
                $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" id="' . $f->id . '" class="delete btn btn-danger btn-sm">Delete</button>';
                return $button;
            })
            ->addIndexColumn() // add column index table in list
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user_data = login::FindOrFail($id);
        $render = [
            'judul' => 'Edit data user',
            'action' => route('user_akses.update', $user_data->id),
            'method' => method_field('put'),
            'username' => $user_data->username,
            'password' => $user_data->password,
            'nama' => $user_data->nama,
            'foto' => $user_data->foto,
            'email' => $user_data->email,
            'level' => $user_data->level,
            'active' => $user_data->active,
            'date_create' => $user_data->date_create,
            'log' => $user_data->log,
            'id_divisi' => $user_data->id_divisi,
        ];
        return view('user_form', compact('render'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = login::findOrFail($id);
        $required = [
            'username' => 'unique:login,username',
            'nama' => 'required',
            'email' => 'required', 
            'foto' => 'mimes:png,bmp,jpg,JPG,jpeg'
        ];
        $error = Validator::make($request->all(), $required);
        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        if ($request->file('foto')) {
            if ($user->foto != '') {
                @unlink('public/foto_user', $user->foto);
            }
            $file = $request->file('foto');
            $file_ext = $file->getClientOriginalExtension();
            $date = Carbon::now();
            $fileinsert = $date->format('y-m-d') . '.' . $file_ext;
            $filename = $request->file('foto')->move('public/foto_user', $fileinsert);
            $dfhoto = ['foto' => $filename];
        } else {
            $dfhoto = ['foto' => $filename];
        }
        $update = [
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'nama' => $request->nama,
            'email' => $request->email,
            'level' => $request->level,
            'active' => $request->active,
        ];
        $userUpdate = array_merge($dfhoto, $update);
        login::find($id)->save($userUpdate);
        return response()->json(['success' => 'data berhasil di simpan']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = login::findOrFail($request->id);
        $image_path = 'public/foto_user/' . $user->foto;
        if (File::exists($image_path)) {
            File::delete($image_path);
        }
        $user->delete();
        return response()->json(['success' => 'Data berhasil di hapus']);
    }
}
