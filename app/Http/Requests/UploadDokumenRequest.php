<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UploadDokumenRequest extends FormRequest
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
            'kategori' => 'required|in:perusahaan,perumahan,paket_teknis',
            'nama_persyaratan' => 'required|string|max:255',
            'berkas' => [
                'required',
                'file',
                File::types(['pdf', 'dwg', 'dxf'])
                    ->max(50 * 1024), // Max 50MB (DWG) atau 10MB (PDF dicek di validator)
            ],
        ];
    }

    /**
     * Validasi mendalam pada ukuran spesifik per tipe dan magic bytes file.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->hasFile('berkas') || !$this->file('berkas')->isValid()) {
                return;
            }

            $file = $this->file('berkas');
            $ext = strtolower($file->getClientOriginalExtension());
            $size = $file->getSize();
            $path = $file->getRealPath();

            // 1. Batasan Ukuran Berdasarkan Format
            if ($ext === 'pdf') {
                if ($size > 10 * 1024 * 1024) {
                    $validator->errors()->add('berkas', 'Ukuran berkas PDF tidak boleh melebihi 10 MB.');
                    return;
                }

                // 2. Magic Bytes Check untuk PDF (%PDF-)
                $head = (string) @file_get_contents($path, false, null, 0, 8);
                if (!str_starts_with($head, '%PDF-')) {
                    $validator->errors()->add('berkas', 'Header berkas tidak valid atau bukan berkas PDF asli (magic bytes mismatch).');
                    return;
                }
            } elseif (in_array($ext, ['dwg', 'dxf'], true)) {
                if ($size > 50 * 1024 * 1024) {
                    $validator->errors()->add('berkas', 'Ukuran berkas CAD/DWG tidak boleh melebihi 50 MB.');
                    return;
                }

                // 2. Magic Bytes Check untuk DWG (AutoCAD: AC10xx atau AC1.x/AC2.x)
                if ($ext === 'dwg') {
                    $head = (string) @file_get_contents($path, false, null, 0, 16);
                    if (!preg_match('/^AC(10|1\.|2\.)/', $head)) {
                        $validator->errors()->add('berkas', 'Berkas tidak dapat dibaca atau bukan berkas AutoCAD DWG yang valid.');
                        return;
                    }
                }
            } else {
                $validator->errors()->add('berkas', 'Format berkas tidak diizinkan. Hanya menerima PDF dan CAD/DWG.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'berkas.required' => 'Berkas persyaratan wajib diunggah.',
            'kategori.required' => 'Kategori dokumen wajib dipilih.',
            'nama_persyaratan.required' => 'Nama persyaratan wajib diisi.',
        ];
    }
}

