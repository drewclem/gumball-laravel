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
        'is_archived',
    ];

    public function scopeSubmissions() {
        return $this->hasMany(Submission::class);
    }

    public function booked() {
        $booked = $this->hasMany(Submission::class)->where('is_booked', 1);
        return $booked;
    }
    
    public function scopeActive() {
        $mutable = Carbon::now()->subDay();

        $current_date = $mutable->toDateTimeString();
        
        $active = Collection::whereRaw('? between start_date and end_date', [$current_date])->orWhere(function($query) use($current_date) {
            $query->where('start_date', '>=', $current_date)->where('end_date', null);
        });

        return $active;
    }
}
