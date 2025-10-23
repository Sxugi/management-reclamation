<?php

namespace App\Http\Requests\RekapitulasiReklamasi;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRekapitulasiReklamasiRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $lahan = $this->route('lahan');

        return [
            'lahan_id' => 'required|exists:lahan,lahan_id',
            'tipe' => 'required|string',
            'tahun' => [
                'required',
                'integer',
                Rule::unique('data_reklamasi')->where(function ($query) use ($lahan) {
                    return $query->where('lahan_id', $lahan->lahan_id)
                                 ->where('tipe', 'rekapitulasi');
                }),
            ],
            
            // Required fields
            'detail.area_penambangan.volume' => 'required|numeric|gte:0',
            'detail.timbunan_tanah_pengakaran.volume' => 'required|numeric|gte:0',
            'detail.timbunan_batuan_samping.volume' => 'required|numeric|gte:0',
            'detail.timbunan_komoditas_tambang.volume' => 'required|numeric|gte:0',
            'detail.timbunan_limbah_fasilitas.volume' => 'required|numeric|gte:0',
            'detail.jalan_tambang.volume' => 'required|numeric|gte:0',
            'detail.kolam_sedimen.volume' => 'required|numeric|gte:0',
            'detail.fasilitas_pengolahan.volume' => 'required|numeric|gte:0',
            'detail.kantor_perumahan.volume' => 'required|numeric|gte:0',
            'detail.bengkel.volume' => 'required|numeric|gte:0',
            'detail.fasilitas_penunjang.volume' => 'required|numeric|gte:0',
            'detail.lahan_selesai_ditambang.volume' => 'required|numeric|gte:0',
            'detail.lahan_aktif_ditambang.volume' => 'required|numeric|gte:0',
            'detail.volume_batuan_samping.volume' => 'required|numeric|gte:0',
            'detail.penimbunan_bekas_tambang.volume' => 'required|numeric|gte:0',
            'detail.penimbunan_diluar_bekas_tambang.volume' => 'required|numeric|gte:0',
            'detail.volume_bekas_tambang.volume' => 'required|numeric|gte:0',
            'detail.volume_diluar_bekas_tambang.volume' => 'required|numeric|gte:0',
            'detail.penataan_tanah.volume' => 'required|numeric|gte:0',
            'detail.penebaran_tanah_pengakaran.volume' => 'required|numeric|gte:0',
            'detail.pengendalian_erosi.volume' => 'required|numeric|gte:0',
            'detail.pemupukan.volume' => 'required|numeric|gte:0',
            'detail.pengadaan_bibit.volume' => 'required|numeric|gte:0',
            'detail.penanaman.volume' => 'required|numeric|gte:0',
            'detail.pemeliharaan_tanaman.volume' => 'required|numeric|gte:0',
            'detail.pekerjaan_sipil.volume' => 'required|numeric|gte:0',
            'detail.stabilisasi_lereng.volume' => 'required|numeric|gte:0',
            'detail.pengamanan_lubang.volume' => 'required|numeric|gte:0',
            'detail.pemulihan_kualitas_air.volume' => 'required|numeric|gte:0',
            'detail.pemeliharaan_lubang.volume' => 'required|numeric|gte:0',
            
            // Optional fields
            'detail.kualitas_tanah.volume' => 'nullable|numeric|gte:0',
            'detail.pencegahan_air_asam.volume' => 'nullable|numeric|gte:0',
            
            // Hidden fields validation
            'detail.*.kegiatan' => 'required|string',
            'detail.*.kategori' => 'required|string',
            'detail.*.satuan' => 'required|string',
        ];
    }

    /**
     * Get the validation messages for the request.
     */
    public function messages(): array
    {
        return [
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.integer' => 'Tahun harus berupa angka.',
            'tahun.unique' => 'Rencana reklamasi untuk tahun ini sudah ada.',
            
            'detail.area_penambangan.volume.required' => 'Data area penambangan harus diisi.',
            'detail.area_penambangan.volume.numeric' => 'Data area penambangan harus berupa angka.',
            'detail.area_penambangan.volume.gte' => 'Data area penambangan tidak boleh negatif.',
            
            'detail.timbunan_tanah_pengakaran.volume.required' => 'Data timbunan tanah zona pengakaran harus diisi.',
            'detail.timbunan_tanah_pengakaran.volume.numeric' => 'Data timbunan tanah zona pengakaran harus berupa angka.',
            'detail.timbunan_tanah_pengakaran.volume.gte' => 'Data timbunan tanah zona pengakaran tidak boleh negatif.',
            
            'detail.timbunan_batuan_samping.volume.required' => 'Data timbunan batuan samping harus diisi.',
            'detail.timbunan_batuan_samping.volume.numeric' => 'Data timbunan batuan samping harus berupa angka.',
            'detail.timbunan_batuan_samping.volume.gte' => 'Data timbunan batuan samping tidak boleh negatif.',
            
            'detail.timbunan_komoditas_tambang.volume.required' => 'Data timbunan komoditas tambang harus diisi.',
            'detail.timbunan_komoditas_tambang.volume.numeric' => 'Data timbunan komoditas tambang harus berupa angka.',
            'detail.timbunan_komoditas_tambang.volume.gte' => 'Data timbunan komoditas tambang tidak boleh negatif.',

            'detail.timbunan_limbah_fasilitas.volume.required' => 'Data timbunan limbah fasilitas penunjang harus diisi.',
            'detail.timbunan_limbah_fasilitas.volume.numeric' => 'Data timbunan limbah fasilitas penunjang harus berupa angka.',
            'detail.timbunan_limbah_fasilitas.volume.gte' => 'Data timbunan limbah fasilitas penunjang tidak boleh negatif.',
            
            'detail.jalan_tambang.volume.required' => 'Data jalan tambang atau jalan angkut harus diisi.',
            'detail.jalan_tambang.volume.numeric' => 'Data jalan tambang atau jalan angkut harus berupa angka.',
            'detail.jalan_tambang.volume.gte' => 'Data jalan tambang atau jalan angkut tidak boleh negatif.',
            
            'detail.kolam_sedimen.volume.required' => 'Data kolam sedimen harus diisi.',
            'detail.kolam_sedimen.volume.numeric' => 'Data kolam sedimen harus berupa angka.',
            'detail.kolam_sedimen.volume.gte' => 'Data kolam sedimen tidak boleh negatif.',
            
            'detail.fasilitas_pengolahan.volume.required' => 'Data instalasi dan fasilitas pengolahan harus diisi.',
            'detail.fasilitas_pengolahan.volume.numeric' => 'Data instalasi dan fasilitas pengolahan harus berupa angka.',
            'detail.fasilitas_pengolahan.volume.gte' => 'Data instalasi dan fasilitas pengolahan tidak boleh negatif.',
            
            'detail.kantor_perumahan.volume.required' => 'Data kantor dan perumahan harus diisi.',
            'detail.kantor_perumahan.volume.numeric' => 'Data kantor dan perumahan harus berupa angka.',
            'detail.kantor_perumahan.volume.gte' => 'Data kantor dan perumahan tidak boleh negatif.',
            
            'detail.bengkel.volume.required' => 'Data bengkel harus diisi.',
            'detail.bengkel.volume.numeric' => 'Data bengkel harus berupa angka.',
            'detail.bengkel.volume.gte' => 'Data bengkel tidak boleh negatif.',
            
            'detail.fasilitas_penunjang.volume.required' => 'Data fasilitas penunjang lainnya harus diisi.',
            'detail.fasilitas_penunjang.volume.numeric' => 'Data fasilitas penunjang lainnya harus berupa angka.',
            'detail.fasilitas_penunjang.volume.gte' => 'Data fasilitas penunjang lainnya tidak boleh negatif.',
            
            'detail.lahan_selesai_ditambang.volume.required' => 'Data lahan selesai ditambang harus diisi.',
            'detail.lahan_selesai_ditambang.volume.numeric' => 'Data lahan selesai ditambang harus berupa angka.',
            'detail.lahan_selesai_ditambang.volume.gte' => 'Data lahan selesai ditambang tidak boleh negatif.',
            
            'detail.lahan_aktif_ditambang.volume.required' => 'Data lahan/front aktif ditambang harus diisi.',
            'detail.lahan_aktif_ditambang.volume.numeric' => 'Data lahan/front aktif ditambang harus berupa angka.',
            'detail.lahan_aktif_ditambang.volume.gte' => 'Data lahan/front aktif ditambang tidak boleh negatif.',
            
            'detail.volume_batuan_samping.volume.required' => 'Data volume batuan samping yang digali harus diisi.',
            'detail.volume_batuan_samping.volume.numeric' => 'Data volume batuan samping harus berupa angka.',
            'detail.volume_batuan_samping.volume.gte' => 'Data volume batuan samping tidak boleh negatif.',
            
            'detail.penimbunan_bekas_tambang.volume.required' => 'Data penimbunan di bekas tambang harus diisi.',
            'detail.penimbunan_bekas_tambang.volume.numeric' => 'Data penimbunan di bekas tambang harus berupa angka.',
            'detail.penimbunan_bekas_tambang.volume.gte' => 'Data penimbunan di bekas tambang tidak boleh negatif.',
            
            'detail.penimbunan_diluar_bekas_tambang.volume.required' => 'Data penimbunan di luar bekas tambang harus diisi.',
            'detail.penimbunan_diluar_bekas_tambang.volume.numeric' => 'Data penimbunan di luar bekas tambang harus berupa angka.',
            'detail.penimbunan_diluar_bekas_tambang.volume.gte' => 'Data penimbunan di luar bekas tambang tidak boleh negatif.',
            
            'detail.volume_bekas_tambang.volume.required' => 'Data volume yang ditimbun di bekas tambang harus diisi.',
            'detail.volume_bekas_tambang.volume.numeric' => 'Data volume yang ditimbun di bekas tambang harus berupa angka.',
            'detail.volume_bekas_tambang.volume.gte' => 'Data volume yang ditimbun di bekas tambang tidak boleh negatif.',
            
            'detail.volume_diluar_bekas_tambang.volume.required' => 'Data volume yang ditimbun di luar bekas tambang harus diisi.',
            'detail.volume_diluar_bekas_tambang.volume.numeric' => 'Data volume yang ditimbun di luar bekas tambang harus berupa angka.',
            'detail.volume_diluar_bekas_tambang.volume.gte' => 'Data volume yang ditimbun di luar bekas tambang tidak boleh negatif.',
            
            'detail.penataan_tanah.volume.required' => 'Data penataan tanah harus diisi.',
            'detail.penataan_tanah.volume.numeric' => 'Data penataan tanah harus berupa angka.',
            'detail.penataan_tanah.volume.gte' => 'Data penataan tanah tidak boleh negatif.',
            
            'detail.penebaran_tanah_pengakaran.volume.required' => 'Data penebaran tanah zona pengakaran harus diisi.',
            'detail.penebaran_tanah_pengakaran.volume.numeric' => 'Data penebaran tanah zona pengakaran harus berupa angka.',
            'detail.penebaran_tanah_pengakaran.volume.gte' => 'Data penebaran tanah zona pengakaran tidak boleh negatif.',
            
            'detail.pengendalian_erosi.volume.required' => 'Data pengendalian erosi dan sedimentasi harus diisi.',
            'detail.pengendalian_erosi.volume.numeric' => 'Data pengendalian erosi dan sedimentasi harus berupa angka.',
            'detail.pengendalian_erosi.volume.gte' => 'Data pengendalian erosi dan sedimentasi tidak boleh negatif.',
            
            'detail.kualitas_tanah.volume.numeric' => 'Data analisis kualitas tanah harus berupa angka.',
            'detail.kualitas_tanah.volume.gte' => 'Data analisis kualitas tanah tidak boleh negatif.',
            
            'detail.pemupukan.volume.required' => 'Data pemupukan harus diisi.',
            'detail.pemupukan.volume.numeric' => 'Data pemupukan harus berupa angka.',
            'detail.pemupukan.volume.gte' => 'Data pemupukan tidak boleh negatif.',
            
            'detail.pengadaan_bibit.volume.required' => 'Data pengadaan bibit harus diisi.',
            'detail.pengadaan_bibit.volume.numeric' => 'Data pengadaan bibit harus berupa angka.',
            'detail.pengadaan_bibit.volume.gte' => 'Data pengadaan bibit tidak boleh negatif.',
            
            'detail.penanaman.volume.required' => 'Data penanaman harus diisi.',
            'detail.penanaman.volume.numeric' => 'Data penanaman harus berupa angka.',
            'detail.penanaman.volume.gte' => 'Data penanaman tidak boleh negatif.',
            
            'detail.pemeliharaan_tanaman.volume.required' => 'Data pemeliharaan tanaman harus diisi.',
            'detail.pemeliharaan_tanaman.volume.numeric' => 'Data pemeliharaan tanaman harus berupa angka.',
            'detail.pemeliharaan_tanaman.volume.gte' => 'Data pemeliharaan tanaman tidak boleh negatif.',
            
            'detail.pencegahan_air_asam.volume.numeric' => 'Data pencegahan air asam harus berupa angka.',
            'detail.pencegahan_air_asam.volume.gte' => 'Data pencegahan air asam tidak boleh negatif.',
            
            'detail.pekerjaan_sipil.volume.required' => 'Data pekerjaan sipil harus diisi.',
            'detail.pekerjaan_sipil.volume.numeric' => 'Data pekerjaan sipil harus berupa angka.',
            'detail.pekerjaan_sipil.volume.gte' => 'Data pekerjaan sipil tidak boleh negatif.',
            
            'detail.stabilisasi_lereng.volume.required' => 'Data stabilisasi lereng harus diisi.',
            'detail.stabilisasi_lereng.volume.numeric' => 'Data stabilisasi lereng harus berupa angka.',
            'detail.stabilisasi_lereng.volume.gte' => 'Data stabilisasi lereng tidak boleh negatif.',
            
            'detail.pengamanan_lubang.volume.required' => 'Data pengamanan lubang bekas tambang harus diisi.',
            'detail.pengamanan_lubang.volume.numeric' => 'Data pengamanan lubang bekas tambang harus berupa angka.',
            'detail.pengamanan_lubang.volume.gte' => 'Data pengamanan lubang bekas tambang tidak boleh negatif.',
            
            'detail.pemulihan_kualitas_air.volume.required' => 'Data pemulihan dan pemantauan kualitas air harus diisi.',
            'detail.pemulihan_kualitas_air.volume.numeric' => 'Data pemulihan dan pemantauan kualitas air harus berupa angka.',
            'detail.pemulihan_kualitas_air.volume.gte' => 'Data pemulihan dan pemantauan kualitas air tidak boleh negatif.',
            
            'detail.pemeliharaan_lubang.volume.required' => 'Data pemeliharaan lubang bekas tambang harus diisi.',
            'detail.pemeliharaan_lubang.volume.numeric' => 'Data pemeliharaan lubang bekas tambang harus berupa angka.',
            'detail.pemeliharaan_lubang.volume.gte' => 'Data pemeliharaan lubang bekas tambang tidak boleh negatif.',
        ];
    }
}
