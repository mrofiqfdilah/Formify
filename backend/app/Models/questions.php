<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class questions extends Model
{
    use HasFactory;
    protected $table = 'questions';
    protected $guarded = ['id'];

    public $timestamps = false;

    public function forms()
    {
        return $this->belongsTo(Forms::class, 'form_id');
    }
}
