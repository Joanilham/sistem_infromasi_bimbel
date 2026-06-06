<?php

namespace App\Http\Requests\Akademik;

use Illuminate\Foundation\Http\FormRequest;

class PaketBimbinganRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Checkboxes return 'on', we want boolean true/false
        $this->merge([
            'bisa_dicicil' => $this->has('bisa_dicicil') ? true : false,
            'is_featured'  => $this->has('is_featured') ? true : false,
        ]);
        
        // Remove non-numeric chars for prices if they contain formatting
        if ($this->has('nominal') && is_string($this->nominal)) {
            $this->merge([
                'nominal' => preg_replace('/[^0-9]/', '', $this->nominal)
            ]);
        }
        
        if ($this->has('harga_coret') && is_string($this->harga_coret)) {
            $this->merge([
                'harga_coret' => preg_replace('/[^0-9]/', '', $this->harga_coret)
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama_paket'        => 'required|string|max:255',
            'nominal'           => 'required|numeric|min:0',
            'dp_persen_minimal' => 'required|integer|min:1|max:100',
            'bisa_dicicil'      => 'required|boolean',
            'max_cicilan'       => 'required_if:bisa_dicicil,1|nullable|integer|min:1',
            'harga_coret'       => 'nullable|numeric|min:0',
            'durasi_jumlah'     => 'nullable|integer|min:1',
            'durasi_satuan'     => 'nullable|string|in:Bulan,Tahun',
            'deskripsi'         => 'nullable|string',
            'benefits'          => 'nullable|string',
            'target_peserta'    => 'nullable|string',
            'fasilitas'         => 'nullable|string',
            'is_featured'       => 'nullable|boolean',
            'label_populer'     => 'nullable|string|max:50',
            'urutan'            => 'nullable|integer',
            'gambar_paket'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'nama_paket.required'        => 'Nama paket wajib diisi.',
            'nominal.required'           => 'Harga promo wajib diisi.',
            'nominal.numeric'            => 'Harga harus berupa angka.',
            'dp_persen_minimal.required' => 'Persentase DP minimal wajib diisi.',
            'dp_persen_minimal.integer'  => 'DP minimal harus berupa angka bulat.',
            'dp_persen_minimal.min'      => 'DP minimal tidak boleh kurang dari 1%.',
            'dp_persen_minimal.max'      => 'DP minimal tidak boleh lebih dari 100%.',
            'gambar_paket.max'           => 'Ukuran gambar terlalu besar. Maksimal adalah 5MB.',
            'gambar_paket.image'         => 'File harus berupa gambar.',
            'gambar_paket.mimes'         => 'Format gambar harus jpg, jpeg, png, atau webp.',
        ];
    }
}
