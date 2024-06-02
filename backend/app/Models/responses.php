<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class responses extends Model
{
    use HasFactory;
    protected $table = 'responses';
    protected $guarded = ['id'];
    public $timestamps = false;
    
    public function forms()
    {
        return $this->belongsTo(Forms::class, 'form_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'response_id');
    }

}   
