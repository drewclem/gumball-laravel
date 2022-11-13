<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagRelation extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'tag_id'
    ];

    public function tag() {
        return $this->belongsTo(Tag::class);
    }
}
