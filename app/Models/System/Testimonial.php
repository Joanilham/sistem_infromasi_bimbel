<?php

namespace App\Models\System;
use App\Models\System\Testimonial;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class Testimonial extends Model
{
    use Auditable;

    protected $fillable = ['nama', 'posisi', 'ulasan', 'foto', 'bintang', 'is_active'];
}


