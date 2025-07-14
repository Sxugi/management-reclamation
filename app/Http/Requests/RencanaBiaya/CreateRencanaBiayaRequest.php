<?php

namespace App\Http\Requests\RencanaBiaya;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRencanaBiayaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $lahan = $this->route('lahan');
        $rencana_biaya = $this->route('rencana_biaya');

        $uniqueTahun = Rule::unique('biaya_reklamasi')
            ->where(function($query) use ($lahan) {
                $query->where('type', 'rencana')
                    ->where('lahan_id', $lahan->lahan_id);
            });

        return [
            'tahun' => ['required', 'integer', $uniqueTahun],
            'penataan_tanah' => 'required|numeric',
            'penebaran_tanah_pengakaran' => 'required|numeric',
            'pengendalian_erosi' => 'required|numeric',
            'kualitas_tanah' => 'required|numeric',
            'pemupukan' => 'required|numeric',
            'pengadaan_bibit' => 'required|numeric',
            'penanaman' => 'required|numeric',
            'pemeliharaan_tanaman' => 'required|numeric',
            'pencegahan_air_asam' => 'required|numeric',
            'pekerjaan_sipil' => 'required|numeric',
            'stabilisasi_lereng' => 'required|numeric',
            'pengamanan_lubang' => 'required|numeric',
            'pemulihan_kualitas_air' => 'required|numeric',
            'pemeliharaan_lubang' => 'required|numeric',
            'subtotal_1' => 'required|numeric',
            'mobilisasi_demobilisasi_alat' => 'required|numeric',
            'perencanaan_reklamasi' => 'required|numeric',
            'administrasi_pihak_ketiga' => 'required|numeric',
            'supervisi' => 'required|numeric',
            'subtotal_2' => 'required|numeric',
        ];
    }

    /**
     * get custom messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'penataan_tanah.required' => 'Data biaya penataan permukaan tanah harus diisi.',
            'penataan_tanah.numeric' => 'Data biaya penataan permukaan tanah harus berupa angka.',
            'penebaran_tanah_pengakaran.required' => 'Data biaya penebaran tanah zona pengakaran harus diisi.',
            'penebaran_tanah_pengakaran.numeric' => 'Data biaya penebaran tanah zona pengakaran harus berupa angka.',
            'pengendalian_erosi.required' => 'Data biaya pengendalian erosi dan sedimentasi harus diisi.',
            'pengendalian_erosi.numeric' => 'Data biaya pengendalian erosi dan sedimentasi harus berupa angka.',
            'kualitas_tanah.required' => 'Data biaya analisis kualitas tanah harus diisi.',
            'kualitas_tanah.numeric' => 'Data biaya analisis kualitas tanah harus berupa angka.',
            'pemupukan.required' => 'Data biaya pemupukan harus diisi.',
            'pemupukan.numeric' => 'Data biaya pemupukan harus berupa angka.',
            'pengadaan_bibit.required' => 'Data biaya pengadaan bibit harus diisi.',
            'pengadaan_bibit.numeric' => 'Data biaya pengadaan bibit harus berupa angka.',
            'penanaman.required' => 'Data biaya penanaman harus diisi.',
            'penanaman.numeric' => 'Data biaya penanaman harus berupa angka.',
            'pemeliharaan_tanaman.required' => 'Data biaya pemeliharaan tanaman harus diisi.',
            'pemeliharaan_tanaman.numeric' => 'Data biaya pemeliharaan tanaman harus berupa angka.',
            'pencegahan_air_asam.required' => 'Data biaya pencegahan dan penanggulangan air asam harus diisi.',
            'pencegahan_air_asam.numeric' => 'Data biaya pencegahan dan penanggulangan air asam harus berupa angka.',
            'pekerjaan_sipil.required' => 'Data biaya pekerjaan sipil harus diisi.',
            'pekerjaan_sipil.numeric' => 'Data biaya pekerjaan sipil harus berupa angka.',
            'stabilisasi_lereng.required' => 'Data biaya stabilisasi lereng harus diisi.',
            'stabilisasi_lereng.numeric' => 'Data biaya stabilisasi lereng harus berupa angka.',
            'pengamanan_lubang.required' => 'Data biaya pengamanan lubang bekas tambang harus diisi.',
            'pengamanan_lubang.numeric' => 'Data biaya pengamanan lubang bekas tambang harus berupa angka.',
            'pemulihan_kualitas_air.required' => 'Data biaya pemulihan dan pemantauan kualitas air harus diisi.',
            'pemulihan_kualitas_air.numeric' => 'Data biaya pemulihan dan pemantauan kualitas air harus berupa angka.',
            'pemeliharaan_lubang.required' => 'Data biaya pemeliharaan lubang bekas tambang harus diisi.',
            'pemeliharaan_lubang.numeric' => 'Data biaya pemeliharaan lubang bekas tambang harus berupa angka.',
            'subtotal_1.required' => 'Data subtotal 1 harus diisi.',
            'subtotal_1.numeric' => 'Data subtotal 1 harus berupa angka.',
            'mobilisasi_demobilisasi_alat.required' => 'Data biaya mobilisasi dan demobilisasi alat harus diisi.',
            'mobilisasi_demobilisasi_alat.numeric' => 'Data biaya mobilisasi dan demobilisasi alat harus berupa angka.',
            'perencanaan_reklamasi.required' => 'Data biaya perencanaan reklamasi harus diisi.',
            'perencanaan_reklamasi.numeric' => 'Data biaya perencanaan reklamasi harus berupa angka.',
            'administrasi_pihak_ketiga.required' => 'Data biaya administrasi dan keuntungan pihak ketiga harus diisi.',
            'administrasi_pihak_ketiga.numeric' => 'Data biaya administrasi dan keuntungan pihak ketiga harus berupa angka.',
            'supervisi.required' => 'Data biaya supervisi harus diisi.',
            'supervisi.numeric' => 'Data biaya supervisi harus berupa angka.',
            'subtotal_2.required' => 'Data subtotal 2 harus diisi.',
            'subtotal_2.numeric' => 'Data subtotal 2 harus berupa angka.',
        ];
    }
}