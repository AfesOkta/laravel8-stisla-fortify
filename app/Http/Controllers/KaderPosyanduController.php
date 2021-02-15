<?php

namespace App\Http\Controllers;

use App\Http\Requests\KaderRequest;
use App\Models\KaderPosyandu;
use App\Models\MstPosyandu;
use App\Repositories\KaderRepository;
use App\Repositories\PosyanduRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class KaderPosyanduController extends Controller
{
    protected $kaderRepository;
    protected $posyanduRepo;
    public function __construct(KaderRepository $kaderRepository, PosyanduRepository $posyanduRepo)
    {
        $this->middleware('auth');
        $this->kaderRepository = $kaderRepository;
        $this->posyanduRepo = $posyanduRepo;
    }

    public function json_list()
    {
        $data = $this->kaderRepository->getAll();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                return '<a href="javascript:void(0)" onclick="edit('.$row->id.')"
                    title="Edit '.$row->kader_nama.'" class="btn btn-info btn-sm btn-icon" data-dismiss="modal"><i class="fas fa-edit">&nbsp;edit</i></a>
                    <a href="javascript:void(0)" onclick="hapus('.$row->id.')"
                    title="Delete '.$row->kader_nama.'" class="btn btn-danger btn-sm btn-icon" data-dismiss="modal"><i class="fas fa-trash">&nbsp;delete</i></a>
                             <meta name="csrf-token" content="{{ csrf_token() }}">';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $posyandus = $this->posyanduRepo->getAll();
        return view('kader.list', compact('posyandus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KaderRequest $request)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();
            $data = $request->all();
            $this->kaderRepository->create($data);
            DB::commit();
            $message = "Tambah data kader berhasil disimpan";
            $status  = True;
        }catch(Exception $ex) {
            Log::debug($ex->getMessage());
            DB::rollback();
            $message = "Tambah data kader tidak berhasil disimpan";
            $status  = False;
        }

        return response()->json(["status" => $status, "message" => $message]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KaderPosyandu  $kaderPosyandu
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $kader = $this->kaderRepository->findFirst($id);
        return response()->json($kader);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KaderPosyandu  $kaderPosyandu
     * @return \Illuminate\Http\Response
     */
    public function update(KaderRequest $request)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();
            $dataAll = $request->all();
            $this->kaderRepository->update($dataAll);
            DB::commit();
            $message = "update data posyandu berhasil disimpan";
            $status  = True;
        }catch(Exception $ex) {
            Log::debug($ex->getMessage());
            DB::rollback();
            $message = "Update data posyandu tidak berhasil disimpan";
            $status  = False;
        }

        return response()->json(["status" => $status, "message" => $message]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KaderPosyandu  $kaderPosyandu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        try{
            DB::beginTransaction();
            $delete = $this->kaderRepository->findFirst($request->id)->delete();
            if ($delete) {
                $message = "Data kader posyandu berhasil dihapus";
                $status  = True;
            }else{
                $message = "Data kader posyandu tidak berhasil dihapus";
                $status  = False;
            }
            DB::commit();
        }catch(Exception $ex) {
            DB::rollBack();
            $message = "Data kader posyandu tidak ditemukan";
            $status  = false;
        }

        return response()->json(["status" => $status, "message" => $message]);
    }

    public function qr_code($id)
    {
        $kader = $this->kaderRepository->findFirst($id);
        $fileDest = 'img/qr-code/img-'.$kader->id.'.png';
        $qrcode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)
                  ->generate("{{url('api/login?n='.$kader->nik.'&p='.$kader->posyandu_id.')}}",
                  storage_path('app/'.$fileDest));

        Storage::disk('local')->put($fileDest, $qrcode);

        $url = url("api/login?n='.$kader->email.'&p='.$kader->posyandu_id");
        $url_down = url(storage_path("app/'+.$fileDest+"));

        return view("kader.qrcode",compact('url','url_down','fileDest'));
    }
}
