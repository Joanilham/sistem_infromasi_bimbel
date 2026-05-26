<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class Testimonial extends Model
{
    use Auditable;

    protected $fillable = ['nama', 'posisi', 'ulasan', 'foto', 'bintang', 'is_active'];
}
