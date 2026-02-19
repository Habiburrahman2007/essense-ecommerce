<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'name',
        'code',
        'sort_order',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
