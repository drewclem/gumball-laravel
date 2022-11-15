<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'user_id',
        'name',
        'email',
        'phone',
        'message',
        'has_viewed',
        'is_saved',
        'is_declined',
        'is_booked',
        'is_liked'
    ];

    public function collection() {
        return $this->hasOne(Collection::class);
    }

    public function scopeImages() {
        return $this->hasMany(SubmissionUpload::class);
    }

    public function scopeTags() {
        return $this->hasMany(TagRelation::class);
    }

}
