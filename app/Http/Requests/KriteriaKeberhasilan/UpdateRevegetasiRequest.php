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
            'rencana_luas_penanaman' => 'required|string|max:255',
            'realisasi_luas_penanaman' => 'required|string|max:255',
            'evaluasi_luas_penanaman' => 'required|string|max:255',
            'rencana_pertumbuhan_tanaman' => 'required|string|max:255',
            'realisasi_pertumbuhan_tanaman' => 'required|string|max:255',
            'evaluasi_pertumbuhan_tanaman' => 'required|string|max:255',
            'rencana_pengelolaan_material' => 'required|string|max:255',
            'realisasi_pengelolaan_material' => 'required|string|max:255',
            'evaluasi_pengelolaan_material' => 'required|string|max:255',
            'rencana_bangunan_erosi' => 'required|string|max:255',
            'realisasi_bangunan_erosi' => 'required|string|max:255',
            'evaluasi_bangunan_erosi' => 'required|string|max:255',
            'rencana_kolam_sedimen' => 'required|string|max:255',
            'realisasi_kolam_sedimen' => 'required|string|max:255',
            'evaluasi_kolam_sedimen' => 'required|string|max:255',
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
            'rencana_luas_penanaman.required' => 'Rencana luas penanaman harus diisi.',
            'rencana_luas_penanaman.numeric' => 'Rencana luas penanaman harus berupa angka.',
            'realisasi_luas_penanaman.required' => 'Realisasi luas penanaman harus diisi.',
            'realisasi_luas_penanaman.numeric' => 'Realisasi luas penanaman harus berupa angka.',
            'evaluasi_luas_penanaman.required' => 'Evaluasi luas penanaman harus diisi.',
            'evaluasi_luas_penanaman.string' => 'Evaluasi luas penanaman harus berupa teks.',
            'rencana_pertumbuhan_tanaman.required' => 'Rencana pertumbuhan tanaman harus diisi.',
            'rencana_pertumbuhan_tanaman.string' => 'Rencana pertumbuhan tanaman harus berupa teks.',
            'realisasi_pertumbuhan_tanaman.required' => 'Realisasi pertumbuhan tanaman harus diisi.',
            'realisasi_pertumbuhan_tanaman.string' => 'Realisasi pertumbuhan tanaman harus berupa teks.',
            'evaluasi_pertumbuhan_tanaman.required' => 'Evaluasi pertumbuhan tanaman harus diisi.',
            'evaluasi_pertumbuhan_tanaman.string' => 'Evaluasi pertumbuhan tanaman harus berupa teks.',
            'rencana_pengelolaan_material.required' => 'Rencana pengelolaan material harus diisi.',
            'rencana_pengelolaan_material.string' => 'Rencana pengelolaan material harus berupa teks.',
            'realisasi_pengelolaan_material.required' => 'Realisasi pengelolaan material harus diisi.',
            'realisasi_pengelolaan_material.string' => 'Realisasi pengelolaan material harus berupa teks.',
            'evaluasi_pengelolaan_material.required' => 'Evaluasi pengelolaan material harus diisi.',
            'evaluasi_pengelolaan_material.string' => 'Evaluasi pengelolaan material harus berupa teks.',
            'rencana_bangunan_erosi.required' => 'Rencana bangunan erosi harus diisi.',
            'rencana_bangunan_erosi.string' => 'Rencana bangunan erosi harus berupa teks.',
            'realisasi_bangunan_erosi.required' => 'Realisasi bangunan erosi harus diisi.',
            'realisasi_bangunan_erosi.string' => 'Realisasi bangunan erosi harus berupa teks.',
            'evaluasi_bangunan_erosi.required' => 'Evaluasi bangunan erosi harus diisi.',
            'evaluasi_bangunan_erosi.string' => 'Evaluasi bangunan erosi harus berupa teks.',
            'rencana_kolam_sedimen.required' => 'Rencana kolam sedimen harus diisi.',
            'rencana_kolam_sedimen.string' => 'Rencana kolam sedimen harus berupa teks.',
            'realisasi_kolam_sedimen.required' => 'Realisasi kolam sedimen harus diisi.',
            'realisasi_kolam_sedimen.string' => 'Realisasi kolam sedimen harus berupa teks.',
            'evaluasi_kolam_sedimen.required' => 'Evaluasi kolam sedimen harus diisi.',
            'evaluasi_kolam_sedimen.string' => 'Evaluasi kolam sedimen harus berupa teks.',
        ];
    }
}
