<?php

namespace App\Http\Controllers;

use App\TmbarangModel;
use App\Login;
use Carbon\Carbon;
use Validator;
use DataTables;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class TmbarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->view = 'tmbarang';
    }

    public function index()
    {
        //
        $data  = [];
        return view($this->view, compact('data'));
    }

    function api(Request $request)
    {
        $data  = TmbarangModel::FindOrfail($request->id);
        $json  = [
            'id' => $data->id,
            'action' => $data->action,
            'barangnm' => $data->barangnm,
            'foto' => $data->foto,
            'hargabeli' => $data->hargabeli,
            'hargajual' => $data->hargajual,
            'stok' => $data->stok,
            'foto' => asset('public/foto/' . $data->foto),
            'method' => 'POST',
            'action' => route('barang.edit_data')
        ];
        return response()->json($json);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function api_table()
    {
        $fdata = TmbarangModel::get();
        return Datatables::of($fdata)
            ->editColumn('action', function ($data) {
                $button = '<button type="button" id="edit" name="edit" data="' . $data->id . '" class="edit btn btn-primary btn-xs"><i class="icon icon-edit"></i>Edit</button>';
                $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="detail" id="detail" data="' . $data->id . '" class="delete btn btn-info btn-xs"><i class="icon icon-book"></i>Detail</button>';
                $button .= '&nbsp;&nbsp;&nbsp;<button type="button" id="delete" name="delete" data="' . $data->id . '" class="btn btn-danger btn-xs"><i class="icon icon-trash"></i><Delete</button>';
                return $button;
            }, true)
            ->editColumn('stok', function ($fdata) {
                $button = '&nbsp;&nbsp;&nbsp;<button class="btn btn-info btn-xs">' . $fdata->stok . '</button>';
                return $button;
            }, true)

            ->editColumn('hjual', function ($fdata) {
                $button = '&nbsp;&nbsp;&nbsp;<b>' . number_format((int) $fdata->hargajual, 0, 0, '.') . '</b>';
                return $button;
            }, true)

            ->editColumn('hbeli', function ($fdata) {
                $button = '&nbsp;&nbsp;&nbsp;<b>' . number_format((int) $fdata->hargabeli, 0, 0, '.') . '</b>';
                return $button;
            }, true)

            ->editColumn('stok', function ($fdata) {
                $button = '&nbsp;&nbsp;&nbsp;<button class="btn btn-info btn-xs">' . $fdata->stok . '</button>';
                return $button;
            }, true)

            ->editColumn('foto_barang', function ($fdata) {
                $foto = '<img src="' . asset('public/foto/' . $fdata->foto) . '" class="img-responsive" style="width:100px;height:100px">';
                return $foto;
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'stok', 'foto_barang', 'hbeli', 'hjual'])
            ->toJson();
    }


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
        $valid = Validator::make($request->all(), [
            'barangnm' => 'required|unique:tmbarang,barangnm',
            'foto' =>  'required|mimes:jpeg,png|max:100',
            'hbeli' => 'required',
            'hjual' => 'required',
            'stok' => 'required',
        ]);
        if ($valid->fails()) {
            return response()->json(['errors' => $valid->errors()->all()], 200);
        }
        if ($request->file('foto')) {
            $getfile   = $request->file('foto')->getClientOriginalExtension();
            $fileMove  = rand(12, 1000) . Carbon::now()->format('Y-m-d-H-i-s') . '.' . $getfile;
            $request->file('foto')->move(public_path('/foto/'), $fileMove);
            $filename = $fileMove;
        }
        $tmdata            = new TmbarangModel();
        $tmdata->foto      = $filename;
        $tmdata->barangnm  = $request->barangnm;
        $tmdata->hargabeli = str_replace(',', '', $request->hbeli);
        $tmdata->hargajual = str_replace(',', '', $request->hjual);
        $tmdata->stok      = $request->stok;
        $tmdata->login_id  = Auth::user()->id;
        $tmdata->save();

        return response()->json(['success' => 'Data berhasil di simpan'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $barang = TmbarangModel::findOrFail($id);
        return view($this->view . '_detail', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_data(Request $request)
    {
       // dd($request->all());
       
       $tdata          = TmbarangModel::find($request->id);
        $valid = Validator::make($request->all(), [
            'barangnm' => 'required',
            'stok'     => 'required',
            'hbeli'    => 'required',
            'hjual'    => 'required',
            'foto'     => 'mimes:jpeg,png|max:100',
        ]);
        if ($valid->fails()) {
            return response()->json(['errors' => $valid->errors()->all()], 200);
        }
        if ($request->file('foto')) {
            $getfile  = $request->file('foto')->getClientOriginalExtension();
            $fileMove  = rand(12, 1000) . Carbon::now()->format('Y-m-d-H-i-s') . '.' . $getfile;
            $request->file('foto')->move(public_path('/foto/'), $fileMove);
            $filename =  $fileMove;
        } else {
            $filename = $tdata->foto;
        }   
        $tmdata            = TmbarangModel::find($request->id);
        $tmdata->foto      = $filename;
        $tmdata->barangnm  = $request->barangnm;
        $tmdata->hargabeli = str_replace(',', '', $request->hbeli);
        $tmdata->hargajual = str_replace(',', '', $request->hjual);
        $tmdata->stok      = $request->stok;
        $tmdata->login_id  = Auth::user()->id;
        $tmdata->save();
        
        return response()->json(['success' => 'Data berhasil di update'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $data = TmbarangModel::find($request->id)->first();
        if ($data->foto != '') {
            @unlink(asset('./foto/' . $data->foto));
        } else { }
        $data->delete();
        return response()->json(['msg' => 'Data berhasil di hapus'], 200);
    }
}
