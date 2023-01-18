<?php

namespace Modules\FrontendCMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoginPage extends Model
{
    use HasFactory;
    protected $table = "login_pages";
    public $timestamps=true;
    protected $guarded = ['id'];
}
