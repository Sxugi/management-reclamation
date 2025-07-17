<?php

namespace App\Http\Requests\KriteriaKeberhasilan;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePenatagunaanRequest extends FormRequest
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
            'rencana_luas_ditata' => 'required|numeric',
            'realisasi_luas_ditata' => 'required|numeric',
            'evaluasi_luas_ditata' => 'required|string|max:255',
            'rencana_stabilitas_ditata' => 'required|string|max:255',
            'realisasi_stabilitas_ditata' => 'required|string|max:255',
            'evaluasi_stabilitas_ditata' => 'required|string|max:255',
            'rencana_luas_ditimbun' => 'required|numeric',
            'realisasi_luas_ditimbun' => 'required|numeric',
            'evaluasi_luas_ditimbun' => 'required|string|max:255',
            'rencana_stabilitas_ditimbun' => 'required|string|max:255',
            'realisasi_stabilitas_ditimbun' => 'required|string|max:255',
            'evaluasi_stabilitas_ditimbun' => 'required|string|max:255',
            'rencana_luas_ditebar' => 'required|numeric',
            'realisasi_luas_ditebar' => 'required|numeric',
            'evaluasi_luas_ditebar' => 'required|string|max:255',
            'rencana_ph_tanah' => 'required|numeric',
            'realisasi_ph_tanah' => 'required|numeric',
            'evaluasi_ph_tanah' => 'required|string|max:255',
            'rencana_saluran_drainase' => 'required|string|max:255',
            'realisasi_saluran_drainase' => 'required|string|max:255',
            'evaluasi_saluran_drainase' => 'required|string|max:255',
            'rencana_pengendalian_erosi' => 'required|string|max:255',
            'realisasi_pengendalian_erosi' => 'required|string|max:255',
            'evaluasi_pengendalian_erosi' => 'required|string|max:255',
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
            'rencana_luas_ditata.required' => 'Rencana luas lahan yang ditata harus diisi.',
            'rencana_luas_ditata.numeric' => 'Rencana luas lahan yang ditata harus berupa angka.',
            'realisasi_luas_ditata.required' => 'Realisasi luas lahan yang ditata harus diisi.',
            'realisasi_luas_ditata.numeric' => 'Realisasi luas lahan yang ditata harus berupa angka.',
            'evaluasi_luas_ditata.required' => 'Evaluasi luas lahan yang ditata harus diisi.',
            'evaluasi_luas_ditata.string' => 'Evaluasi luas lahan yang ditata harus berupa teks.',
            'rencana_stabilitas_ditata.required' => 'Rencana stabilitas lahan yang ditata harus diisi.',
            'rencana_stabilitas_ditata.string' => 'Rencana stabilitas lahan yang ditata harus berupa teks.',
            'realisasi_stabilitas_ditata.required' => 'Realisasi stabilitas lahan yang ditata harus diisi.',
            'realisasi_stabilitas_ditata.string' => 'Realisasi stabilitas lahan yang ditata harus berupa teks.',
            'evaluasi_stabilitas_ditata.required' => 'Evaluasi stabilitas lahan yang ditata harus diisi.',
            'evaluasi_stabilitas_ditata.string' => 'Evaluasi stabilitas lahan yang ditata harus berupa teks.',
            'rencana_luas_ditimbun.required' => 'Rencana luas lahan yang ditimbun harus diisi.',
            'rencana_luas_ditimbun.numeric' => 'Rencana luas lahan yang ditimbun harus berupa angka.',
            'realisasi_luas_ditimbun.required' => 'Realisasi luas lahan yang ditimbun harus diisi.',
            'realisasi_luas_ditimbun.numeric' => 'Realisasi luas lahan yang ditimbun harus berupa angka.',
            'evaluasi_luas_ditimbun.required' => 'Evaluasi luas lahan yang ditimbun harus diisi.',
            'evaluasi_luas_ditimbun.string' => 'Evaluasi luas lahan yang ditimbun harus berupa teks.',
            'rencana_stabilitas_ditimbun.required' => 'Rencana stabilitas lahan yang ditimbun harus diisi.',
            'rencana_stabilitas_ditimbun.string' => 'Rencana stabilitas lahan yang ditimbun harus berupa teks.',
            'realisasi_stabilitas_ditimbun.required' => 'Realisasi stabilitas lahan yang ditimbun harus diisi.',
            'realisasi_stabilitas_ditimbun.string' => 'Realisasi stabilitas lahan yang ditimbun harus berupa teks.',
            'evaluasi_stabilitas_ditimbun.required' => 'Evaluasi stabilitas lahan yang ditimbun harus diisi.',
            'evaluasi_stabilitas_ditimbun.string' => 'Evaluasi stabilitas lahan yang ditimbun harus berupa teks.',
            'rencana_luas_ditebar.required' => 'Rencana luas lahan yang ditebar harus diisi.',
            'rencana_luas_ditebar.numeric' => 'Rencana luas lahan yang ditebar harus berupa angka.',
            'realisasi_luas_ditebar.required' => 'Realisasi luas lahan yang ditebar harus diisi.',
            'realisasi_luas_ditebar.numeric' => 'Realisasi luas lahan yang ditebar harus berupa angka.',
            'evaluasi_luas_ditebar.required' => 'Evaluasi luas lahan yang ditebar harus diisi.',
            'evaluasi_luas_ditebar.string' => 'Evaluasi luas lahan yang ditebar harus berupa teks.',
            'rencana_ph_tanah.required' => 'Rencana pH tanah harus diisi.',
            'rencana_ph_tanah.numeric' => 'Rencana pH tanah harus berupa angka.',
            'realisasi_ph_tanah.required' => 'Realisasi pH tanah harus diisi.',
            'realisasi_ph_tanah.numeric' => 'Realisasi pH tanah harus berupa angka.',
            'evaluasi_ph_tanah.required' => 'Evaluasi pH tanah harus diisi.',
            'evaluasi_ph_tanah.string' => 'Evaluasi pH tanah harus berupa teks.',
            'rencana_saluran_drainase.required' => 'Rencana saluran drainase harus diisi.',
            'rencana_saluran_drainase.string' => 'Rencana saluran drainase harus berupa teks.',
            'realisasi_saluran_drainase.required' => 'Realisasi saluran drainase harus diisi.',
            'realisasi_saluran_drainase.string' => 'Realisasi saluran drainase harus berupa teks.',
            'evaluasi_saluran_drainase.required' => 'Evaluasi saluran drainase harus diisi.',
            'evaluasi_saluran_drainase.string' => 'Evaluasi saluran drainase harus berupa teks.',
            'rencana_pengendalian_erosi.required' => 'Rencana pengendalian erosi harus diisi.',
            'rencana_pengendalian_erosi.string' => 'Rencana pengendalian erosi harus berupa teks.',
            'realisasi_pengendalian_erosi.required' => 'Realisasi pengendalian erosi harus diisi.',
            'realisasi_pengendalian_erosi.string' => 'Realisasi pengendalian erosi harus berupa teks.',
            'evaluasi_pengendalian_erosi.required' => 'Evaluasi pengendalian erosi harus diisi.',
            'evaluasi_pengendalian_erosi.string' => 'Evaluasi pengendalian erosi harus berupa teks.',
        ];
    }
}
