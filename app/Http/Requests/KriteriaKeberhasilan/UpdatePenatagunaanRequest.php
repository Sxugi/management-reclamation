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
            'indikator' => 'required|array',

            'indikator.luas_ditata.rencana' => 'required|numeric',
            'indikator.luas_ditata.realisasi' => 'required|numeric',
            'indikator.luas_ditata.hasil_evaluasi' => 'required|string|max:255',

            'indikator.stabilitas_ditata.rencana' => 'required|string|max:255',
            'indikator.stabilitas_ditata.realisasi' => 'required|string|max:255',
            'indikator.stabilitas_ditata.hasil_evaluasi' => 'required|string|max:255',

            'indikator.luas_ditimbun.rencana' => 'required|numeric',
            'indikator.luas_ditimbun.realisasi' => 'required|numeric',
            'indikator.luas_ditimbun.hasil_evaluasi' => 'required|string|max:255',

            'indikator.stabilitas_ditimbun.rencana' => 'required|string|max:255',
            'indikator.stabilitas_ditimbun.realisasi' => 'required|string|max:255',
            'indikator.stabilitas_ditimbun.hasil_evaluasi' => 'required|string|max:255',

            'indikator.luas_ditebar.rencana' => 'required|numeric',
            'indikator.luas_ditebar.realisasi' => 'required|numeric',
            'indikator.luas_ditebar.hasil_evaluasi' => 'required|string|max:255',

            'indikator.ph_tanah.rencana' => 'required|numeric',
            'indikator.ph_tanah.realisasi' => 'required|numeric',
            'indikator.ph_tanah.hasil_evaluasi' => 'required|string|max:255',

            'indikator.saluran_drainase.rencana' => 'required|string|max:255',
            'indikator.saluran_drainase.realisasi' => 'required|string|max:255',
            'indikator.saluran_drainase.hasil_evaluasi' => 'required|string|max:255',

            'indikator.pengendalian_erosi.rencana' => 'required|string|max:255',
            'indikator.pengendalian_erosi.realisasi' => 'required|string|max:255',
            'indikator.pengendalian_erosi.hasil_evaluasi' => 'required|string|max:255',
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
            'indikator.luas_ditata.rencana.required' => 'Rencana luas lahan yang ditata harus diisi.',
            'indikator.luas_ditata.rencana.numeric' => 'Rencana luas lahan yang ditata harus berupa angka.',
            'indikator.luas_ditata.realisasi.required' => 'Realisasi luas lahan yang ditata harus diisi.',
            'indikator.luas_ditata.realisasi.numeric' => 'Realisasi luas lahan yang ditata harus berupa angka.',
            'indikator.luas_ditata.hasil_evaluasi.required' => 'Evaluasi luas lahan yang ditata harus diisi.',
            'indikator.luas_ditata.hasil_evaluasi.string' => 'Evaluasi luas lahan yang ditata harus berupa teks.',
            'indikator.stabilitas_ditata.rencana.required' => 'Rencana stabilitas lahan yang ditata harus diisi.',
            'indikator.stabilitas_ditata.rencana.string' => 'Rencana stabilitas lahan yang ditata harus berupa teks.',
            'indikator.stabilitas_ditata.realisasi.required' => 'Realisasi stabilitas lahan yang ditata harus diisi.',
            'indikator.stabilitas_ditata.realisasi.string' => 'Realisasi stabilitas lahan yang ditata harus berupa teks.',
            'indikator.stabilitas_ditata.hasil_evaluasi.required' => 'Evaluasi stabilitas lahan yang ditata harus diisi.',
            'indikator.stabilitas_ditata.hasil_evaluasi.string' => 'Evaluasi stabilitas lahan yang ditata harus berupa teks.',
            'indikator.luas_ditimbun.rencana.required' => 'Rencana luas lahan yang ditimbun harus diisi.',
            'indikator.luas_ditimbun.rencana.numeric' => 'Rencana luas lahan yang ditimbun harus berupa angka.',
            'indikator.luas_ditimbun.realisasi.required' => 'Realisasi luas lahan yang ditimbun harus diisi.',
            'indikator.luas_ditimbun.realisasi.numeric' => 'Realisasi luas lahan yang ditimbun harus berupa angka.',
            'indikator.luas_ditimbun.hasil_evaluasi.required' => 'Evaluasi luas lahan yang ditimbun harus diisi.',
            'indikator.luas_ditimbun.hasil_evaluasi.string' => 'Evaluasi luas lahan yang ditimbun harus berupa teks.',
            'indikator.stabilitas_ditimbun.rencana.required' => 'Rencana stabilitas lahan yang ditimbun harus diisi.',
            'indikator.stabilitas_ditimbun.rencana.string' => 'Rencana stabilitas lahan yang ditimbun harus berupa teks.',
            'indikator.stabilitas_ditimbun.realisasi.required' => 'Realisasi stabilitas lahan yang ditimbun harus diisi.',
            'indikator.stabilitas_ditimbun.realisasi.string' => 'Realisasi stabilitas lahan yang ditimbun harus berupa teks.',
            'indikator.stabilitas_ditimbun.hasil_evaluasi.required' => 'Evaluasi stabilitas lahan yang ditimbun harus diisi.',
            'indikator.stabilitas_ditimbun.hasil_evaluasi.string' => 'Evaluasi stabilitas lahan yang ditimbun harus berupa teks.',
            'indikator.luas_ditebar.rencana.required' => 'Rencana luas lahan yang ditebar harus diisi.',
            'indikator.luas_ditebar.rencana.numeric' => 'Rencana luas lahan yang ditebar harus berupa angka.',
            'indikator.luas_ditebar.realisasi.required' => 'Realisasi luas lahan yang ditebar harus diisi.',
            'indikator.luas_ditebar.realisasi.numeric' => 'Realisasi luas lahan yang ditebar harus berupa angka.',
            'indikator.luas_ditebar.hasil_evaluasi.required' => 'Evaluasi luas lahan yang ditebar harus diisi.',
            'indikator.luas_ditebar.hasil_evaluasi.string' => 'Evaluasi luas lahan yang ditebar harus berupa teks.',
            'indikator.ph_tanah.rencana.required' => 'Rencana pH tanah harus diisi.',
            'indikator.ph_tanah.rencana.numeric' => 'Rencana pH tanah harus berupa angka.',
            'indikator.ph_tanah.realisasi.required' => 'Realisasi pH tanah harus diisi.',
            'indikator.ph_tanah.realisasi.numeric' => 'Realisasi pH tanah harus berupa angka.',
            'indikator.ph_tanah.hasil_evaluasi.required' => 'Evaluasi pH tanah harus diisi.',
            'indikator.ph_tanah.hasil_evaluasi.string' => 'Evaluasi pH tanah harus berupa teks.',
            'indikator.saluran_drainase.rencana.required' => 'Rencana saluran drainase harus diisi.',
            'indikator.saluran_drainase.rencana.string' => 'Rencana saluran drainase harus berupa teks.',
            'indikator.saluran_drainase.realisasi.required' => 'Realisasi saluran drainase harus diisi.',
            'indikator.saluran_drainase.realisasi.string' => 'Realisasi saluran drainase harus berupa teks.',
            'indikator.saluran_drainase.hasil_evaluasi.required' => 'Evaluasi saluran drainase harus diisi.',
            'indikator.saluran_drainase.hasil_evaluasi.string' => 'Evaluasi saluran drainase harus berupa teks.',
            'indikator.pengendalian_erosi.rencana.required' => 'Rencana pengendalian erosi harus diisi.',
            'indikator.pengendalian_erosi.rencana.string' => 'Rencana pengendalian erosi harus berupa teks.',
            'indikator.pengendalian_erosi.realisasi.required' => 'Realisasi pengendalian erosi harus diisi.',
            'indikator.pengendalian_erosi.realisasi.string' => 'Realisasi pengendalian erosi harus berupa teks.',
            'indikator.pengendalian_erosi.hasil_evaluasi.required' => 'Evaluasi pengendalian erosi harus diisi.',
            'indikator.pengendalian_erosi.hasil_evaluasi.string' => 'Evaluasi pengendalian erosi harus berupa teks.',
        ];
    }
}
