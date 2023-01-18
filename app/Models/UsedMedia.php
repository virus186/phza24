<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsedMedia extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function usable()
    {
        return $this->morphTo();
    }
}
