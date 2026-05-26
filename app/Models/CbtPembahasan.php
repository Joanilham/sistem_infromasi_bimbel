<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class CbtPembahasan extends Model
{
    use Auditable;

    protected $table = 'cbt_pembahasans';
    protected $guarded = [];

    public function bankSoal()
    {
        return $this->belongsTo(CbtBankSoal::class, 'cbt_bank_soal_id');
    }

    /**
     * Menghapus kode/syntax dari pembahasan
     * Hanya menampilkan penjelasan jawaban saja
     */
    public function getPembahasanBersihAttribute()
    {
        $text = $this->teks_pembahasan;

        // Hapus HTML tags
        $text = strip_tags($text);

        // Hapus backticks dan kode inline
        $text = preg_replace('/`[^`]*`/', '', $text);
        
        // Hapus kode blok (keduanya {}, [], atau indentation)
        $text = preg_replace('/```[\s\S]*?```/', '', $text);
        $text = preg_replace('/~~~[\s\S]*?~~~/', '', $text);
        
        // Hapus baris dengan kode (dimulai dengan > atau $)
        $text = preg_replace('/^[\s]*(>|$|const|let|var|function|class|public|private|protected)\b.*$/m', '', $text);
        
        // Hapus multiple line breaks
        $text = preg_replace('/\n\n+/', "\n\n", $text);

        // Trim whitespace
        $text = trim($text);

        return $text;
    }
}
