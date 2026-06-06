<?php

namespace App\Models\System;
use App\Models\System\Faq;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class Faq extends Model
{
    use Auditable;

    protected $fillable = ['pertanyaan', 'jawaban', 'urutan', 'is_active'];
}


