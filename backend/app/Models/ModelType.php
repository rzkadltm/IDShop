<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModelType extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'item_type',
        'content'
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => url('/storage/modeltypes/' . $image),
        );
    }

    public function advertisement()
    {
        return $this->hasMany(Advertisement::class);
    }
}