<?php

namespace App\Models\System;
use App\Models\User;
use App\Models\System\Message;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['type'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'conversation_user');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}


