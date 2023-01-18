<?php
namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Translatable\HasTranslations;

class Attribute extends Model
{
    use HasFactory , HasTranslations;
    protected $table = "attributes";
    protected $guarded = ["id"];
    public $translatable = [];
    public function __construct()
    {
        parent::__construct();
        if (isModuleActive('FrontendMultiLang')) {
            $this->translatable = ['name','description'];
        }
    }
    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function colors(){
        return $this->hasManyThrough(Color::class,AttributeValue::class,'attribute_id','attribute_value_id','id','id');
    }

    public static function boot()
    {
        parent::boot();
        static::created(function ($attribute) {
            $attribute->created_by = Auth::user()->id ?? null;
        });

        static::updating(function ($attribute) {
            $attribute->updated_by = Auth::user()->id ?? null;
        });
    }
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
