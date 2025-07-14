<?php

namespace App\Http\Requests\RekapitulasiReklamasi;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRekapitulasiReklamasiRequest extends FormRequest
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
        $rekapitulasi_reklamasi = $this->route('rekapitulasi_reklamasi');

        $uniqueTahun = Rule::unique('data_reklamasi')
            ->where(function($query) use ($lahan) {
                $query->where('type', 'rekapitulasi')
                    ->where('lahan_id', $lahan->lahan_id);
            })
            ->ignore($rekapitulasi_reklamasi->data_reklamasi_id, 'data_reklamasi_id');

        return [
            'tahun' => ['required', 'integer', $uniqueTahun],
            'area_penambangan' => 'required|numeric',
            'timbuman_tanah_pengakaran' => 'required|numeric',
            'timbuman_batuan_samping' => 'required|numeric',
            'timbuman_komoditas_tambang' => 'required|numeric',
            'timbuman_limbah_fasilitas' => 'required|numeric',
            'jalan_tambang' => 'required|numeric',
            'kolam_sedimen' => 'required|numeric',
            'fasilitas_pengolahan' => 'required|numeric',
            'kantor_perumahan' => 'required|numeric',
            'bengkel' => 'required|numeric',
            'fasilitas_penunjang' => 'required|numeric',
            'lahan_selesai_ditambang' => 'required|numeric',
            'lahan_aktif_ditambang' => 'required|numeric',
            'volume_batuan_samping' => 'required|numeric',
            'penimbunan_bekas_tambang' => 'required|numeric',
            'penimbunan_diluar_bekas_tambang' => 'required|numeric',
            'volume_bekas_tambang' => 'required|numeric',
            'volume_diluar_bekas_tambang' => 'required|numeric',
            'penataan_tanah' => 'required|numeric',
            'penebaran_tanah_pengakaran' => 'required|numeric',
            'pengendalian_erosi' => 'required|numeric',
            'kualitas_tanah' => 'numeric',
            'pemupukan' => 'required|numeric',
            'pengadaan_bibit' => 'required|numeric',
            'penanaman' => 'required|numeric',
            'pemeliharaan_tanaman' => 'required|numeric',
            'pencegahan_air_asam' => 'numeric',
            'pekerjaan_sipil' => 'required|numeric',
            'stabilisasi_lereng' => 'required|numeric',
            'pengamanan_lubang' => 'required|numeric',
            'pemulihan_kualitas_air' => 'required|numeric',
            'pemeliharaan_lubang' => 'required|numeric'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'area_penambangan.required' => 'Data area penambangan harus diisi.',
            'area_penambangan.numeric' => 'Data area penambangan harus berupa angka.',
            'timbuman_tanah_pengakaran.required' => 'Data timbuman tanah zona pengakaran harus diisi.',
            'timbuman_tanah_pengakaran.numeric' => 'Data timbuman tanah zona pengakaran harus berupa angka.',
            'timbuman_batuan_samping.required' => 'Data timbuman batuan samping harus diisi.',
            'timbuman_batuan_samping.numeric' => 'Data timbuman batuan samping harus berupa angka.',
            'timbuman_komoditas_tambang.required' => 'Data timbuman komoditas tambang harus diisi.',
            'timbuman_komoditas_tambang.numeric' => 'Data timbuman komoditas tambang harus berupa angka.',
            'timbuman_limbah_fasilitas.required' => 'Data timbuman limbah fasilitas harus diisi.',
            'timbuman_limbah_fasilitas.numeric' => 'Data timbuman limbah fasilitas harus berupa angka.',
            'jalan_tambang.required' => 'Data jalan tambang atau jalan angkut harus diisi.',
            'jalan_tambang.numeric' => 'Data jalan tambang atau jalan angkut harus berupa angka.',
            'kolam_sedimen.required' => 'Data kolam sedimen harus diisi.',
            'kolam_sedimen.numeric' => 'Data kolam sedimen harus berupa angka.',
            'fasilitas_pengolahan.required' => 'Data instalasi dan fasilitas pengolahan harus diisi.',
            'fasilitas_pengolahan.numeric' => 'Data instalasi dan fasilitas pengolahan harus berupa angka.',
            'kantor_perumahan.required' => 'Data kantor dan perumahan harus diisi.',
            'kantor_perumahan.numeric' => 'Data kantor dan perumahan harus berupa angka.',
            'bengkel.required' => 'Data bengkel harus diisi.',
            'bengkel.numeric' => 'Data bengkel harus berupa angka.',
            'fasilitas_penunjang.required' => 'Data fasilitas penunjang lainnya harus diisi.',
            'fasilitas_penunjang.numeric' => 'Data fasilitas penunjang lainnya harus berupa angka.',
            'lahan_selesai_ditambang.required' => 'Data lahan selesai ditambang harus diisi.',
            'lahan_selesai_ditambang.numeric' => 'Data lahan selesai ditambang harus berupa angka.',
            'lahan_aktif_ditambang.required' => 'Data lahan/front aktif ditambang harus diisi.',
            'lahan_aktif_ditambang.numeric' => 'Data lahan/front aktif ditambang harus berupa angka.',
            'volume_batuan_samping.required' => 'Data volume batuan samping yang digali harus diisi.',
            'volume_batuan_samping.numeric' => 'Data volume batuan samping harus berupa angka.',
            'penimbunan_bekas_tambang.required' => 'Data penimbunan di bekas tambang harus diisi.',
            'penimbunan_bekas_tambang.numeric' => 'Data penimbunan di bekas tambang harus berupa angka.',
            'penimbunan_diluar_bekas_tambang.required' => 'Data penimbunan di luar bekas tambang harus diisi.',
            'penimbunan_diluar_bekas_tambang.numeric' => 'Data penimbunan di luar bekas tambang harus berupa angka.',
            'volume_bekas_tambang.required' => 'Data volume yang ditimbun di bekas tambang harus diisi.',
            'volume_bekas_tambang.numeric' => 'Data volume yang ditimbun di bekas tambang harus berupa angka.',
            'volume_diluar_bekas_tambang.required' => 'Data volume yang ditimbun di luar bekas tambang harus diisi.',
            'volume_diluar_bekas_tambang.numeric' => 'Data volume yang ditimbun di luar bekas tambang harus berupa angka.',
            'penataan_tanah.required' => 'Data penataan tanah harus diisi.',
            'penataan_tanah.numeric' => 'Data penataan tanah harus berupa angka.',
            'penebaran_tanah_pengakaran.required' => 'Data penebaran tanah zona pengakaran harus diisi.',
            'penebaran_tanah_pengakaran.numeric' => 'Data penebaran tanah zona pengakaran harus berupa angka.',
            'pengendalian_erosi.required' => 'Data pengendalian erosi dan sedimentasi harus diisi.',
            'pengendalian_erosi.numeric' => 'Data pengendalian erosi dan sedimentasi harus berupa angka.',
            'kualitas_tanah.numeric' => 'Data analisis kualitas tanah harus berupa angka.',
            'pemupukan.required' => 'Data pemupukan harus diisi.',
            'pemupukan.numeric' => 'Data pemupukan harus berupa angka.',
            'pengadaan_bibit.required' => 'Data pengadaan bibit harus diisi.',
            'pengadaan_bibit.numeric' => 'Data pengadaan bibit harus berupa angka.',
            'penanaman.required' => 'Data penanaman harus diisi.',
            'penanaman.numeric' => 'Data penanaman harus berupa angka.',
            'pemeliharaan_tanaman.required' => 'Data pemeliharaan tanaman harus diisi.',
            'pemeliharaan_tanaman.numeric' => 'Data pemeliharaan tanaman harus berupa angka.',
            'pencegahan_air_asam.numeric' => 'Data pencegahan air asam harus berupa angka.',
            'pekerjaan_sipil.required' => 'Data pekerjaan sipil harus diisi.',
            'pekerjaan_sipil.numeric' => 'Data pekerjaan sipil harus berupa angka.',
            'stabilisasi_lereng.required' => 'Data stabilisasi lereng harus diisi.',
            'stabilisasi_lereng.numeric' => 'Data stabilisasi lereng harus berupa angka.',
            'pengamanan_lubang.required' => 'Data pengamanan lubang bekas tambang harus diisi.',
            'pengamanan_lubang.numeric' => 'Data pengamanan lubang bekas tambang harus berupa angka.',
            'pemulihan_kualitas_air.required' => 'Data pemulihan dan pemantauan kualitas air harus diisi.',
            'pemulihan_kualitas_air.numeric' => 'Data pemulihan dan pemantauan kualitas air harus berupa angka.',
            'pemeliharaan_lubang.required' => 'Data pemeliharaan lubang bekas tambang harus diisi.',
            'pemeliharaan_lubang.numeric' => 'Data pemeliharaan lubang bekas tambang harus berupa angka.'
        ];
    }
}
