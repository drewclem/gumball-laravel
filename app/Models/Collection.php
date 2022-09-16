<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'isArchived',
    ];

    public function submissions() {
        return $this->hasMany(Submission::class);
    }

    public function booked() {
        $booked = $this->hasMany(Submission::class)->where('is_booked', 1);
        return $booked;
    }
}
