<?php

namespace App\Http\Requests\PlotHandover;

use Illuminate\Foundation\Http\FormRequest;

class StoreHandoverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by policy
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'luas' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'lokasi' => ['required', 'string', 'max:1000'],
            'tanggal' => ['required', 'date', 'before_or_equal:today'],
            'surat' => ['nullable', 'array', 'max:10'],
            'surat.*' => ['file', 'mimes:pdf', 'max:10240'], // 10MB
            'peta' => ['nullable', 'array', 'max:10'],
            'peta.*' => ['file', 'mimes:pdf,jpg,jpeg,png,kml,kmz,geojson', 'max:20480'], // 20MB
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'luas' => 'luas lahan',
            'lokasi' => 'lokasi',
            'tanggal' => 'tanggal serah terima',
            'surat' => 'surat serah terima',
            'surat.*' => 'file surat',
            'peta' => 'peta lahan',
            'peta.*' => 'file peta',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Luas
            'luas.required' => 'Luas lahan wajib diisi.',
            'luas.numeric' => 'Luas lahan harus berupa angka.',
            'luas.min' => 'Luas lahan tidak boleh kurang dari 0.',
            'luas.max' => 'Luas lahan terlalu besar (maksimal 999.999,99 Ha).',

            // Lokasi
            'lokasi.required' => 'Lokasi wajib diisi.',
            'lokasi.string' => 'Lokasi harus berupa teks.',
            'lokasi.max' => 'Lokasi terlalu panjang (maksimal 1000 karakter).',

            // Tanggal
            'tanggal.required' => 'Tanggal serah terima wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'tanggal.before_or_equal' => 'Tanggal serah terima tidak boleh lebih dari hari ini.',

            // Surat Array
            'surat.array' => 'Format data surat tidak valid.',
            'surat.max' => 'Maksimal 10 file surat yang dapat diupload.',

            // Surat Files
            'surat.*.file' => 'File surat harus berupa file yang valid.',
            'surat.*.mimes' => 'File surat harus berformat PDF.',
            'surat.*.max' => 'Ukuran file surat tidak boleh lebih dari 10MB.',

            // Peta Array
            'peta.array' => 'Format data peta tidak valid.',
            'peta.max' => 'Maksimal 10 file peta yang dapat diupload.',

            // Peta Files
            'peta.*.file' => 'File peta harus berupa file yang valid.',
            'peta.*.mimes' => 'File peta harus berformat PDF, JPG, PNG, KML, KMZ, atau GeoJSON.',
            'peta.*.max' => 'Ukuran file peta tidak boleh lebih dari 20MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Trim whitespace from lokasi
        if ($this->has('lokasi')) {
            $this->merge([
                'lokasi' => trim($this->lokasi),
            ]);
        }

        // Convert luas to proper decimal format
        if ($this->has('luas')) {
            $this->merge([
                'luas' => str_replace(',', '.', $this->luas),
            ]);
        }
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors();

        // Group file errors by type for better UX
        if ($errors->has('surat.*')) {
            $suratErrors = [];
            foreach ($errors->get('surat.*') as $error) {
                $suratErrors[] = $error;
            }
            session()->flash('surat_errors', $suratErrors);
        }

        if ($errors->has('peta.*')) {
            $petaErrors = [];
            foreach ($errors->get('peta.*') as $error) {
                $petaErrors[] = $error;
            }
            session()->flash('peta_errors', $petaErrors);
        }

        parent::failedValidation($validator);
    }
}