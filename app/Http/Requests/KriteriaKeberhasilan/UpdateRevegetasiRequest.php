<?php

namespace App\Http\Requests\KriteriaKeberhasilan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRevegetasiRequest extends FormRequest
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
        return [
            'indikator' => 'required|array',

            'indikator.luas_penutup.rencana' => 'required|numeric',
            'indikator.luas_penutup.realisasi' => 'required|numeric',
            'indikator.luas_penutup.hasil_evaluasi' => 'required|string|max:255',

            'indikator.luas_cepat_tumbuh.rencana' => 'required|numeric',
            'indikator.luas_cepat_tumbuh.realisasi' => 'required|numeric',
            'indikator.luas_cepat_tumbuh.hasil_evaluasi' => 'required|string|max:255',

            'indikator.luas_lokal.rencana' => 'required|numeric',
            'indikator.luas_lokal.realisasi' => 'required|numeric',
            'indikator.luas_lokal.hasil_evaluasi' => 'required|string|max:255',

            'indikator.pertumbuhan_penutup.rencana' => 'required|string|max:255',
            'indikator.pertumbuhan_penutup.realisasi' => 'required|string|max:255',
            'indikator.pertumbuhan_penutup.hasil_evaluasi' => 'required|string|max:255',

            'indikator.pertumbuhan_cepat_tumbuh.rencana' => 'required|string|max:255',
            'indikator.pertumbuhan_cepat_tumbuh.realisasi' => 'required|string|max:255',
            'indikator.pertumbuhan_cepat_tumbuh.hasil_evaluasi' => 'required|string|max:255',

            'indikator.pertumbuhan_lokal.rencana' => 'required|string|max:255',
            'indikator.pertumbuhan_lokal.realisasi' => 'required|string|max:255',
            'indikator.pertumbuhan_lokal.hasil_evaluasi' => 'required|string|max:255',

            'indikator.pengelolaan_material.rencana' => 'required|string|max:255',
            'indikator.pengelolaan_material.realisasi' => 'required|string|max:255',
            'indikator.pengelolaan_material.hasil_evaluasi' => 'required|string|max:255',

            'indikator.bangunan_erosi.rencana' => 'required|string|max:255',
            'indikator.bangunan_erosi.realisasi' => 'required|string|max:255',
            'indikator.bangunan_erosi.hasil_evaluasi' => 'required|string|max:255',

            'indikator.kolam_sedimen.rencana' => 'required|string|max:255',
            'indikator.kolam_sedimen.realisasi' => 'required|string|max:255',
            'indikator.kolam_sedimen.hasil_evaluasi' => 'required|string|max:255',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'indikator.luas_penutup.rencana.required' => 'Rencana luas area tanaman penutup harus diisi.',
            'indikator.luas_penutup.rencana.numeric' => 'Rencana luas area tanaman penutup harus berupa angka.',
            'indikator.luas_penutup.realisasi.required' => 'Realisasi luas area tanaman penutup harus diisi.',
            'indikator.luas_penutup.realisasi.numeric' => 'Realisasi luas area tanaman penutup harus berupa angka.',
            'indikator.luas_penutup.hasil_evaluasi.required' => 'Evaluasi luas area tanaman penutup harus diisi.',
            'indikator.luas_penutup.hasil_evaluasi.string' => 'Evaluasi luas area tanaman penutup harus berupa teks.',
            'indikator.luas_cepat_tumbuh.rencana.required' => 'Rencana luas area tanaman cepat tumbuh harus diisi.',
            'indikator.luas_cepat_tumbuh.rencana.numeric' => 'Rencana luas area tanaman cepat tumbuh harus berupa angka.',
            'indikator.luas_cepat_tumbuh.realisasi.required' => 'Realisasi luas area tanaman cepat tumbuh harus diisi.',
            'indikator.luas_cepat_tumbuh.realisasi.numeric' => 'Realisasi luas area tanaman cepat tumbuh harus berupa angka.',
            'indikator.luas_cepat_tumbuh.hasil_evaluasi.required' => 'Evaluasi luas area tanaman cepat tumbuh harus diisi.',
            'indikator.luas_cepat_tumbuh.hasil_evaluasi.string' => 'Evaluasi luas area tanaman cepat tumbuh harus berupa teks.',
            'indikator.luas_lokal.rencana.required' => 'Rencana luas area tanaman lokal harus diisi.',
            'indikator.luas_lokal.rencana.numeric' => 'Rencana luas area tanaman lokal harus berupa angka.',
            'indikator.luas_lokal.realisasi.required' => 'Realisasi luas area tanaman lokal harus diisi.',
            'indikator.luas_lokal.realisasi.numeric' => 'Realisasi luas area tanaman lokal harus berupa angka.',
            'indikator.luas_lokal.hasil_evaluasi.required' => 'Evaluasi luas area tanaman lokal harus diisi.',
            'indikator.luas_lokal.hasil_evaluasi.string' => 'Evaluasi luas area tanaman lokal harus berupa teks.',
            'indikator.pertumbuhan_penutup.rencana.required' => 'Rencana pertumbuhan tanaman penutup harus diisi.',
            'indikator.pertumbuhan_penutup.rencana.string' => 'Rencana pertumbuhan tanaman penutup harus berupa teks.',
            'indikator.pertumbuhan_penutup.realisasi.required' => 'Realisasi pertumbuhan tanaman penutup harus diisi.',
            'indikator.pertumbuhan_penutup.realisasi.string' => 'Realisasi pertumbuhan tanaman penutup harus berupa teks.',
            'indikator.pertumbuhan_penutup.hasil_evaluasi.required' => 'Evaluasi pertumbuhan tanaman penutup harus diisi.',
            'indikator.pertumbuhan_penutup.hasil_evaluasi.string' => 'Evaluasi pertumbuhan tanaman penutup harus berupa teks.',
            'indikator.pertumbuhan_cepat_tumbuh.rencana.required' => 'Rencana pertumbuhan tanaman cepat tumbuh harus diisi.',
            'indikator.pertumbuhan_cepat_tumbuh.rencana.string' => 'Rencana pertumbuhan tanaman cepat tumbuh harus berupa teks.',
            'indikator.pertumbuhan_cepat_tumbuh.realisasi.required' => 'Realisasi pertumbuhan tanaman cepat tumbuh harus diisi.',
            'indikator.pertumbuhan_cepat_tumbuh.realisasi.string' => 'Realisasi pertumbuhan tanaman cepat tumbuh harus berupa teks.',
            'indikator.pertumbuhan_cepat_tumbuh.hasil_evaluasi.required' => 'Evaluasi pertumbuhan tanaman cepat tumbuh harus diisi.',
            'indikator.pertumbuhan_cepat_tumbuh.hasil_evaluasi.string' => 'Evaluasi pertumbuhan tanaman cepat tumbuh harus berupa teks.',
            'indikator.pertumbuhan_lokal.rencana.required' => 'Rencana pertumbuhan tanaman lokal harus diisi.',
            'indikator.pertumbuhan_lokal.rencana.string' => 'Rencana pertumbuhan tanaman lokal harus berupa teks.',
            'indikator.pertumbuhan_lokal.realisasi.required' => 'Realisasi pertumbuhan tanaman lokal harus diisi.',
            'indikator.pertumbuhan_lokal.realisasi.string' => 'Realisasi pertumbuhan tanaman lokal harus berupa teks.',
            'indikator.pertumbuhan_lokal.hasil_evaluasi.required' => 'Evaluasi pertumbuhan tanaman lokal harus diisi.',
            'indikator.pertumbuhan_lokal.hasil_evaluasi.string' => 'Evaluasi pertumbuhan tanaman lokal harus berupa teks.',
            'indikator.pengelolaan_material.rencana.required' => 'Rencana pengelolaan material harus diisi.',
            'indikator.pengelolaan_material.rencana.string' => 'Rencana pengelolaan material harus berupa teks.',
            'indikator.pengelolaan_material.realisasi.required' => 'Realisasi pengelolaan material harus diisi.',
            'indikator.pengelolaan_material.realisasi.string' => 'Realisasi pengelolaan material harus berupa teks.',
            'indikator.pengelolaan_material.hasil_evaluasi.required' => 'Evaluasi pengelolaan material harus diisi.',
            'indikator.pengelolaan_material.hasil_evaluasi.string' => 'Evaluasi pengelolaan material harus berupa teks.',
            'indikator.bangunan_erosi.rencana.required' => 'Rencana bangunan erosi harus diisi.',
            'indikator.bangunan_erosi.rencana.string' => 'Rencana bangunan erosi harus berupa teks.',
            'indikator.bangunan_erosi.realisasi.required' => 'Realisasi bangunan erosi harus diisi.',
            'indikator.bangunan_erosi.realisasi.string' => 'Realisasi bangunan erosi harus berupa teks.',
            'indikator.bangunan_erosi.hasil_evaluasi.required' => 'Evaluasi bangunan erosi harus diisi.',
            'indikator.bangunan_erosi.hasil_evaluasi.string' => 'Evaluasi bangunan erosi harus berupa teks.',
            'indikator.kolam_sedimen.rencana.required' => 'Rencana kolam sedimen harus diisi.',
            'indikator.kolam_sedimen.rencana.string' => 'Rencana kolam sedimen harus berupa teks.',
            'indikator.kolam_sedimen.realisasi.required' => 'Realisasi kolam sedimen harus diisi.',
            'indikator.kolam_sedimen.realisasi.string' => 'Realisasi kolam sedimen harus berupa teks.',
            'indikator.kolam_sedimen.hasil_evaluasi.required' => 'Evaluasi kolam sedimen harus diisi.',
            'indikator.kolam_sedimen.hasil_evaluasi.string' => 'Evaluasi kolam sedimen harus berupa teks.',
        ];
    }
}