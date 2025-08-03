<?php

namespace App\Http\Requests\RencanaBiaya;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRencanaBiayaRequest extends FormRequest
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

        return [
            'lahan_id' => 'required|exists:lahan,lahan_id',
            'tipe' => 'required|string',
            'tahun' => [
                'required',
                'integer',
                Rule::unique('biaya_reklamasi')->where(function ($query) use ($lahan) {
                    return $query->where('lahan_id', $lahan->lahan_id)
                                 ->where('tipe', 'rencana');
                })->ignore($rencana_biaya->biaya_reklamasi_id, 'biaya_reklamasi_id'),
            ],
            // Detail Biaya Langsung
            'detail.penataan_tanah.biaya' => 'required|numeric|min:0',
            'detail.penebaran_tanah_pengakaran.biaya' => 'required|numeric|min:0',
            'detail.pengendalian_erosi.biaya' => 'required|numeric|min:0',
            'detail.kualitas_tanah.biaya' => 'required|numeric|min:0',
            'detail.pemupukan.biaya' => 'required|numeric|min:0',
            'detail.pengadaan_bibit.biaya' => 'required|numeric|min:0',
            'detail.penanaman.biaya' => 'required|numeric|min:0',
            'detail.pemeliharaan_tanaman.biaya' => 'required|numeric|min:0',
            'detail.pencegahan_air_asam.biaya' => 'required|numeric|min:0',
            'detail.pekerjaan_sipil.biaya' => 'required|numeric|min:0',
            'detail.stabilisasi_lereng.biaya' => 'required|numeric|min:0',
            'detail.pengamanan_lubang.biaya' => 'required|numeric|min:0',
            'detail.pemulihan_kualitas_air.biaya' => 'required|numeric|min:0',
            'detail.pemeliharaan_lubang.biaya' => 'required|numeric|min:0',

            // SUBTOTAL 1
            'subtotal_1' => 'required|numeric|min:0',

            // Detail Biaya Tidak Langsung
            'detail.mobilisasi_demobilisasi_alat.biaya' => 'required|numeric|min:0',
            'detail.perencanaan_reklamasi.biaya' => 'required|numeric|min:0',
            'detail.administrasi_pihak_ketiga.biaya' => 'required|numeric|min:0',
            'detail.supervisi.biaya' => 'required|numeric|min:0',

            // SUBTOTAL 2
            'subtotal_2' => 'required|numeric|min:0',

            // Hidden fields for each detail
            'detail.*.kategori' => 'required|string|in:biaya_langsung,biaya_tidak_langsung',
            'detail.*.kegiatan' => 'required|string',
        ];
    }

    /**
     * get custom messages for validation rules.
     */
    public function messages(): array
    {
        return [
            // Tahun
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.integer' => 'Tahun harus berupa angka.',
            'tahun.unique' => 'Rencana biaya reklamasi untuk tahun ini sudah ada.',

            // Tipe & Lahan
            'tipe.required' => 'Tipe wajib diisi.',
            'tipe.string' => 'Tipe harus berupa teks.',
            'lahan_id.required' => 'Lahan wajib diisi.',
            'lahan_id.exists' => 'Lahan tidak ditemukan.',

            // Biaya Langsung
            'detail.penataan_tanah.biaya.required' => 'Data biaya penataan permukaan tanah harus diisi.',
            'detail.penataan_tanah.biaya.numeric' => 'Data biaya penataan permukaan tanah harus berupa angka.',
            'detail.penataan_tanah.biaya.min' => 'Data biaya penataan permukaan tanah tidak boleh negatif.',

            'detail.penebaran_tanah_pengakaran.biaya.required' => 'Data biaya penebaran tanah zona pengakaran harus diisi.',
            'detail.penebaran_tanah_pengakaran.biaya.numeric' => 'Data biaya penebaran tanah zona pengakaran harus berupa angka.',
            'detail.penebaran_tanah_pengakaran.biaya.min' => 'Data biaya penebaran tanah zona pengakaran tidak boleh negatif.',

            'detail.pengendalian_erosi.biaya.required' => 'Data biaya pengendalian erosi dan sedimentasi harus diisi.',
            'detail.pengendalian_erosi.biaya.numeric' => 'Data biaya pengendalian erosi dan sedimentasi harus berupa angka.',
            'detail.pengendalian_erosi.biaya.min' => 'Data biaya pengendalian erosi dan sedimentasi tidak boleh negatif.',

            'detail.kualitas_tanah.biaya.required' => 'Data biaya analisis kualitas tanah harus diisi.',
            'detail.kualitas_tanah.biaya.numeric' => 'Data biaya analisis kualitas tanah harus berupa angka.',
            'detail.kualitas_tanah.biaya.min' => 'Data biaya analisis kualitas tanah tidak boleh negatif.',

            'detail.pemupukan.biaya.required' => 'Data biaya pemupukan harus diisi.',
            'detail.pemupukan.biaya.numeric' => 'Data biaya pemupukan harus berupa angka.',
            'detail.pemupukan.biaya.min' => 'Data biaya pemupukan tidak boleh negatif.',

            'detail.pengadaan_bibit.biaya.required' => 'Data biaya pengadaan bibit harus diisi.',
            'detail.pengadaan_bibit.biaya.numeric' => 'Data biaya pengadaan bibit harus berupa angka.',
            'detail.pengadaan_bibit.biaya.min' => 'Data biaya pengadaan bibit tidak boleh negatif.',

            'detail.penanaman.biaya.required' => 'Data biaya penanaman harus diisi.',
            'detail.penanaman.biaya.numeric' => 'Data biaya penanaman harus berupa angka.',
            'detail.penanaman.biaya.min' => 'Data biaya penanaman tidak boleh negatif.',

            'detail.pemeliharaan_tanaman.biaya.required' => 'Data biaya pemeliharaan tanaman harus diisi.',
            'detail.pemeliharaan_tanaman.biaya.numeric' => 'Data biaya pemeliharaan tanaman harus berupa angka.',
            'detail.pemeliharaan_tanaman.biaya.min' => 'Data biaya pemeliharaan tanaman tidak boleh negatif.',

            'detail.pencegahan_air_asam.biaya.required' => 'Data biaya pencegahan dan penanggulangan air asam harus diisi.',
            'detail.pencegahan_air_asam.biaya.numeric' => 'Data biaya pencegahan dan penanggulangan air asam harus berupa angka.',
            'detail.pencegahan_air_asam.biaya.min' => 'Data biaya pencegahan dan penanggulangan air asam tidak boleh negatif.',

            'detail.pekerjaan_sipil.biaya.required' => 'Data biaya pekerjaan sipil harus diisi.',
            'detail.pekerjaan_sipil.biaya.numeric' => 'Data biaya pekerjaan sipil harus berupa angka.',
            'detail.pekerjaan_sipil.biaya.min' => 'Data biaya pekerjaan sipil tidak boleh negatif.',

            'detail.stabilisasi_lereng.biaya.required' => 'Data biaya stabilisasi lereng harus diisi.',
            'detail.stabilisasi_lereng.biaya.numeric' => 'Data biaya stabilisasi lereng harus berupa angka.',
            'detail.stabilisasi_lereng.biaya.min' => 'Data biaya stabilisasi lereng tidak boleh negatif.',

            'detail.pengamanan_lubang.biaya.required' => 'Data biaya pengamanan lubang bekas tambang harus diisi.',
            'detail.pengamanan_lubang.biaya.numeric' => 'Data biaya pengamanan lubang bekas tambang harus berupa angka.',
            'detail.pengamanan_lubang.biaya.min' => 'Data biaya pengamanan lubang bekas tambang tidak boleh negatif.',

            'detail.pemulihan_kualitas_air.biaya.required' => 'Data biaya pemulihan dan pemantauan kualitas air harus diisi.',
            'detail.pemulihan_kualitas_air.biaya.numeric' => 'Data biaya pemulihan dan pemantauan kualitas air harus berupa angka.',
            'detail.pemulihan_kualitas_air.biaya.min' => 'Data biaya pemulihan dan pemantauan kualitas air tidak boleh negatif.',

            'detail.pemeliharaan_lubang.biaya.required' => 'Data biaya pemeliharaan lubang bekas tambang harus diisi.',
            'detail.pemeliharaan_lubang.biaya.numeric' => 'Data biaya pemeliharaan lubang bekas tambang harus berupa angka.',
            'detail.pemeliharaan_lubang.biaya.min' => 'Data biaya pemeliharaan lubang bekas tambang tidak boleh negatif.',

            // SUBTOTAL 1
            'subtotal_1.required' => 'Subtotal 1 harus diisi.',
            'subtotal_1.numeric' => 'Subtotal 1 harus berupa angka.',
            'subtotal_1.min' => 'Subtotal 1 tidak boleh negatif.',

            // Biaya Tidak Langsung
            'detail.mobilisasi_demobilisasi_alat.biaya.required' => 'Data biaya mobilisasi dan demobilisasi alat harus diisi.',
            'detail.mobilisasi_demobilisasi_alat.biaya.numeric' => 'Data biaya mobilisasi dan demobilisasi alat harus berupa angka.',
            'detail.mobilisasi_demobilisasi_alat.biaya.min' => 'Data biaya mobilisasi dan demobilisasi alat tidak boleh negatif.',

            'detail.perencanaan_reklamasi.biaya.required' => 'Data biaya perencanaan reklamasi harus diisi.',
            'detail.perencanaan_reklamasi.biaya.numeric' => 'Data biaya perencanaan reklamasi harus berupa angka.',
            'detail.perencanaan_reklamasi.biaya.min' => 'Data biaya perencanaan reklamasi tidak boleh negatif.',

            'detail.administrasi_pihak_ketiga.biaya.required' => 'Data biaya administrasi dan keuntungan pihak ketiga harus diisi.',
            'detail.administrasi_pihak_ketiga.biaya.numeric' => 'Data biaya administrasi dan keuntungan pihak ketiga harus berupa angka.',
            'detail.administrasi_pihak_ketiga.biaya.min' => 'Data biaya administrasi dan keuntungan pihak ketiga tidak boleh negatif.',

            'detail.supervisi.biaya.required' => 'Data biaya supervisi harus diisi.',
            'detail.supervisi.biaya.numeric' => 'Data biaya supervisi harus berupa angka.',
            'detail.supervisi.biaya.min' => 'Data biaya supervisi tidak boleh negatif.',

            // SUBTOTAL 2
            'subtotal_2.required' => 'Subtotal 2 harus diisi.',
            'subtotal_2.numeric' => 'Subtotal 2 harus berupa angka.',
            'subtotal_2.min' => 'Subtotal 2 tidak boleh negatif.',

            // Hidden fields
            'detail.*.kategori.required' => 'Kategori biaya wajib diisi.',
            'detail.*.kategori.in' => 'Kategori biaya harus biaya_langsung atau biaya_tidak_langsung.',
            'detail.*.kegiatan.required' => 'Nama kegiatan biaya wajib diisi.',
            'detail.*.kegiatan.string' => 'Nama kegiatan biaya harus berupa teks.',
        ];
    }
}
