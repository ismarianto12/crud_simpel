<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Validator;
use App\Login as login;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $id   = Auth::user()->id;
        $data = login::find($id)->first();

        return view('user_login/user_form', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatesave(Request $request)
    {
        $id   = Auth::user()->id;
        $user = login::find($id)->first();
        $required = [
            'name' => 'required',
            'password' => 'required',
            'foto' => 'mimes:png,bmp,jpg,JPG,jpeg'
        ];
        $error = Validator::make($request->all(), $required);
        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        if ($request->file('foto')) {
            if ($user->foto != '') {
                @unlink('public/foto_user/', $user->foto);
            }
            $file = $request->file('foto');
            $file_ext   = $file->getClientOriginalExtension();
            $date       = Carbon::now();
            $fileinsert = rand(1, 2000) . $date->format('y-m-d') . '.' . $file_ext;
            $filename   = $request->file('foto')->move('public/foto_user/', $fileinsert);
            $dfhoto     = $fileinsert;
        } else {
            $dfhoto    = $user->foto;
        } 
        $data           = Login::find($id);
        $data->password = bcrypt($request->password);
        $data->name     = $request->name;
        $data->email    = $request->email;
        $data->foto     = $dfhoto;
        $data->save();
        return response()->json(['success' => 'data berhasil di simpan']);      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
