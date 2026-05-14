<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['nama', 'posisi', 'ulasan', 'foto', 'bintang', 'is_active'];
}
