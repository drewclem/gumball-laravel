<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label'
    ];

    public function usage() {
        return $this->hasMany(TagRelation::class);
    }
}
