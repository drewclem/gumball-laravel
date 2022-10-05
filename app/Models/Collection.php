<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
    
    public function scopeActive() {
        $current_date = Carbon::now()->toDateTimeString();
        
        $active = Collection::whereRaw('? between start_date and end_date', [$current_date]);

        return $active;
    }
}
