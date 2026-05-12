<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;
    protected $table = 'soals';
    protected $guarded = [];

    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class, 'bank_soal_id');
    }

    public function ujian()
    {
        return $this->belongsToMany(Ujian::class, 'ujian_soal');
    }
}