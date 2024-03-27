<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'title',
        'address',
        'model_name',
        'description',
        'modeltype_id',
        'user_id',
    ];

    protected function image($image): Attribute
    {
        return Attribute::make(
            get: fn($image) => url('/storage/advertisement/'.$image),
        );
    }

    public function modelType()
    {
        return $this->belongsTo(ModelType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
