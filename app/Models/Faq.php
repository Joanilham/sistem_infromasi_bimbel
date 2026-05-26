<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class Faq extends Model
{
    use Auditable;

    protected $fillable = ['pertanyaan', 'jawaban', 'urutan', 'is_active'];
}
