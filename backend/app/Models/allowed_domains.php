<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class allowed_domains extends Model
{
    use HasFactory;
    protected $table = 'allowed_domains';
    protected $guarded = ['id'];

    public $timestamps = false;

    public function forms()
    {
        return $this->belongsTo(Forms::class, 'form_id');
    }
}
