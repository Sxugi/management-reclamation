<?php

namespace App\Http\Requests\KriteriaKeberhasilan;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePenyelesaianRequest extends FormRequest
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

            'indikator.penutupan_tajuk.rencana' => 'required|numeric',
            'indikator.penutupan_tajuk.realisasi' => 'required|numeric',
            'indikator.penutupan_tajuk.hasil_evaluasi' => 'required|string|max:255',

            'indikator.pemupukan.rencana' => 'required|string|max:255',
            'indikator.pemupukan.realisasi' => 'required|string|max:255',
            'indikator.pemupukan.hasil_evaluasi' => 'required|string|max:255',

            'indikator.pengendalian_hama.rencana' => 'required|string|max:255',
            'indikator.pengendalian_hama.realisasi' => 'required|string|max:255',
            'indikator.pengendalian_hama.hasil_evaluasi' => 'required|string|max:255',

            'indikator.penyulaman.rencana' => 'required|string|max:255',
            'indikator.penyulaman.realisasi' => 'required|string|max:255',
            'indikator.penyulaman.hasil_evaluasi' => 'required|string|max:255',
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
            'indikator.penutupan_tajuk.rencana.required' => 'Rencana penutupan tajuk harus diisi.',
            'indikator.penutupan_tajuk.rencana.numeric' => 'Rencana penutupan tajuk harus berupa angka.',
            'indikator.penutupan_tajuk.realisasi.required' => 'Realisasi penutupan tajuk harus diisi.',
            'indikator.penutupan_tajuk.realisasi.numeric' => 'Realisasi penutupan tajuk harus berupa angka.',
            'indikator.penutupan_tajuk.hasil_evaluasi.required' => 'Evaluasi penutupan tajuk harus diisi.',
            'indikator.penutupan_tajuk.hasil_evaluasi.string' => 'Evaluasi penutupan tajuk harus berupa teks.',
            'indikator.pemupukan.rencana.required' => 'Rencana pemupukan harus diisi.',
            'indikator.pemupukan.rencana.string' => 'Rencana pemupukan harus berupa teks.',
            'indikator.pemupukan.realisasi.required' => 'Realisasi pemupukan harus diisi.',
            'indikator.pemupukan.realisasi.string' => 'Realisasi pemupukan harus berupa teks.',
            'indikator.pemupukan.hasil_evaluasi.required' => 'Evaluasi pemupukan harus diisi.',
            'indikator.pemupukan.hasil_evaluasi.string' => 'Evaluasi pemupukan harus berupa teks.',
            'indikator.pengendalian_hama.rencana.required' => 'Rencana pengendalian hama harus diisi.',
            'indikator.pengendalian_hama.rencana.string' => 'Rencana pengendalian hama harus berupa teks.',
            'indikator.pengendalian_hama.realisasi.required' => 'Realisasi pengendalian hama harus diisi.',
            'indikator.pengendalian_hama.realisasi.string' => 'Realisasi pengendalian hama harus berupa teks.',
            'indikator.pengendalian_hama.hasil_evaluasi.required' => 'Evaluasi pengendalian hama harus diisi.',
            'indikator.pengendalian_hama.hasil_evaluasi.string' => 'Evaluasi pengendalian hama harus berupa teks.',
            'indikator.penyulaman.rencana.required' => 'Rencana penyulaman harus diisi.',
            'indikator.penyulaman.rencana.string' => 'Rencana penyulaman harus berupa teks.',
            'indikator.penyulaman.realisasi.required' => 'Realisasi penyulaman harus diisi.',
            'indikator.penyulaman.realisasi.string' => 'Realisasi penyulaman harus berupa teks.',
            'indikator.penyulaman.hasil_evaluasi.required' => 'Evaluasi penyulaman harus diisi.',
            'indikator.penyulaman.hasil_evaluasi.string' => 'Evaluasi penyulaman harus berupa teks.',
        ];
    }
}
