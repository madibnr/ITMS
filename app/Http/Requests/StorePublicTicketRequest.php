<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePublicTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Public — no auth required
    }

    public function rules(): array
    {
        return [
            // Reporter data
            'full_name' => 'required|string|max:255',
            'nik' => 'required|string|max:50',
            'whatsapp' => 'required|string|max:20|regex:/^[0-9+\-\s]+$/',
            'email' => 'required|email|max:255',

            // Ticket data
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:Low,Medium,High,Critical',

            // Attachment (optional)
            'attachment' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx',
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'nik.required' => 'NIK (Nomor Induk Karyawan) wajib diisi.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.regex' => 'Format nomor WhatsApp tidak valid.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'title.required' => 'Judul laporan wajib diisi.',
            'description.required' => 'Deskripsi kendala wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak valid.',
            'priority.required' => 'Prioritas wajib dipilih.',
            'attachment.max' => 'Ukuran file maksimal 5MB.',
            'attachment.mimes' => 'File harus bertipe: jpg, png, pdf, doc, docx.',
        ];
    }
}
