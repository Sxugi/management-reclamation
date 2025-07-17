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
            'rencana_penutupan_tajuk' => 'required|numeric',
            'realisasi_penutupan_tajuk' => 'required|numeric',
            'evaluasi_penutupan_tajuk' => 'required|string|max:255',
            'rencana_pemupukan' => 'required|string|max:255',
            'realisasi_pemupukan' => 'required|string|max:255',
            'evaluasi_pemupukan' => 'required|string|max:255',
            'rencana_pengendalian_hama' => 'required|string|max:255',
            'realisasi_pengendalian_hama' => 'required|string|max:255',
            'evaluasi_pengendalian_hama' => 'required|string|max:255',
            'rencana_penyulaman' => 'required|string|max:255',
            'realisasi_penyulaman' => 'required|string|max:255',
            'evaluasi_penyulaman' => 'required|string|max:255',
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
            'rencana_penutupan_tajuk.required' => 'Rencana penutupan tajuk harus diisi.',
            'rencana_penutupan_tajuk.numeric' => 'Rencana penutupan tajuk harus berupa angka.',
            'realisasi_penutupan_tajuk.required' => 'Realisasi penutupan tajuk harus diisi.',
            'realisasi_penutupan_tajuk.numeric' => 'Realisasi penutupan tajuk harus berupa angka.',
            'evaluasi_penutupan_tajuk.required' => 'Evaluasi penutupan tajuk harus diisi.',
            'evaluasi_penutupan_tajuk.string' => 'Evaluasi penutupan tajuk harus berupa teks.',
            'rencana_pemupukan.required' => 'Rencana pemupukan harus diisi.',
            'rencana_pemupukan.string' => 'Rencana pemupukan harus berupa teks.',
            'realisasi_pemupukan.required' => 'Realisasi pemupukan harus diisi.',
            'realisasi_pemupukan.string' => 'Realisasi pemupukan harus berupa teks.',
            'evaluasi_pemupukan.required' => 'Evaluasi pemupukan harus diisi.',
            'evaluasi_pemupukan.string' => 'Evaluasi pemupukan harus berupa teks.',
            'rencana_pengendalian_hama.required' => 'Rencana pengendalian hama harus diisi.',
            'rencana_pengendalian_hama.string' => 'Rencana pengendalian hama harus berupa teks.',
            'realisasi_pengendalian_hama.required' => 'Realisasi pengendalian hama harus diisi.',
            'realisasi_pengendalian_hama.string' => 'Realisasi pengendalian hama harus berupa teks.',
            'evaluasi_pengendalian_hama.required' => 'Evaluasi pengendalian hama harus diisi.',
            'evaluasi_pengendalian_hama.string' => 'Evaluasi pengendalian hama harus berupa teks.',
            'rencana_penyulaman.required' => 'Rencana penyulaman harus diisi.',
            'rencana_penyulaman.string' => 'Rencana penyulaman harus berupa teks.',
            'realisasi_penyulaman.required' => 'Realisasi penyulaman harus diisi.',
            'realisasi_penyulaman.string' => 'Realisasi penyulaman harus berupa teks.',
            'evaluasi_penyulaman.required' => 'Evaluasi penyulaman harus diisi.',
            'evaluasi_penyulaman.string' => 'Evaluasi penyulaman harus berupa teks.',
        ];
    }
}
