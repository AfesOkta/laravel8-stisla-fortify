<?php
namespace App\Repositories;

use App\Models\LansiaPosyandu;
use Illuminate\Support\Facades\Auth;

class AnggotaRepository
{
    protected $lansiaPosyandu;
    protected $posyanduRepo;
    public function __construct(LansiaPosyandu $lansiaPosyandu)
    {
        $this->lansiaPosyandu = $lansiaPosyandu;
    }

    public function getAll() {
        return $this->lansiaPosyandu->with('posyandu')->get();
    }

    public function findAnggotaByEmail($email)
    {
        return $this->lansiaPosyandu->where('email','=',"$email")->first();
    }

    public function findFirst($id)
    {
        return $this->lansiaPosyandu->find($id);
    }

    public function findByColumn($column, $value)
    {
        // return $this->lansiaPosyandu->where($column, $value)->get();
        // return $this->lansiaPosyandu->where([
        //     ['lansia', '=', 'aaaa'],
        //     ['lansia', '=', 'aaaa'],
        //     ['lansia', '=', 'aaaa'],
        // ])->get();
        // $column = ['lansia', 'nama'];
        // $value = ['aaa', 'bbb'];
        $count = is_array($column) ? count($column) : 0;
        $lansia = $this->lansiaPosyandu;
        if ($count != 0) {
            for ($i=0; $i < $count; $i++) {
                $lansia->where($column[$i], $value[$i]);
            }
        } else {
            $lansia->where($column, $value);
        }
        return $lansia->get();
    }

    public function findByAnggotaAndPosyandu($anggota, $posyandu)
    {
        # code...
        return $this->lansiaPosyandu->where('lansia_kode','=', $anggota)->where('posyandu_kode','=', $posyandu)->first();
    }

    // public function findByColumnArray($column[])
    // {
    //     return $this->lansiaPosyandu->where($column, $value)->get();
    //     // return $this->lansiaPosyandu->where([
    //     //     ['lansia', '=', 'aaaa'],
    //     //     ['lansia', '=', 'aaaa'],
    //     //     ['lansia', '=', 'aaaa'],
    //     // ])->get();
    // }

    public function findAnggotaByNikAndPosyandu($nik, $posyandu)
    {
        return $this->lansiaPosyandu->where('lansia_nik','=',$nik)->where('posyandu_kode','=',$posyandu)->first();
    }

    public function create($validateData)
    {
        $data = [
            LansiaPosyandu::POSYANDU_KODE => $validateData['posyandu_kode'],
            LansiaPosyandu::LANSIA_KODE => $validateData['lansia_kode'],
            LansiaPosyandu::LANSIA_NAMA => $validateData['lansia_nama'],
            LansiaPosyandu::LANSIA_ALAMAT => $validateData['lansia_alamat'],
            LansiaPosyandu::LANSIA_NIK => $validateData['lansia_nik'],
            LansiaPosyandu::LANSIA_TELP => $validateData['lansia_telp'],
            LansiaPosyandu::LANSIA_KK => $validateData['lansia_kk'],
            LansiaPosyandu::USER_ID => Auth::user()->id,
            LansiaPosyandu::EMAIL   => $validateData['email'],
        ];
        return $this->lansiaPosyandu->create($data);
    }

    public function update($validateData)
    {
        $data = [
            LansiaPosyandu::POSYANDU_KODE => $validateData['posyandu_kode'],
            LansiaPosyandu::LANSIA_KODE => $validateData['lansia_kode'],
            LansiaPosyandu::LANSIA_NAMA => $validateData['lansia_nama'],
            LansiaPosyandu::LANSIA_ALAMAT => $validateData['lansia_alamat'],
            LansiaPosyandu::LANSIA_NIK => $validateData['lansia_nik'],
            LansiaPosyandu::LANSIA_TELP => $validateData['lansia_telp'],
            LansiaPosyandu::LANSIA_KK => $validateData['lansia_kk'],
            LansiaPosyandu::USER_ID => Auth::user()->id,
            LansiaPosyandu::EMAIL   => $validateData['email'],
        ];

        return $this->lansiaPosyandu->find($validateData['lansia_id'])->update($data);
    }

    public function countPosyandu($posyandu_kode)
    {
        # code...
        return $this->lansiaPosyandu->where('posyandu_kode', $posyandu_kode)->count();
    }
}
