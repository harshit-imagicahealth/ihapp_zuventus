<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class DRJourneyFrame extends Model
{
    use SoftDeletes;
    protected $table = 'dr_journey_frame';
    protected $guarded = [
        'id'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'dob',
    ];
    protected $casts = [  // ✅ CORRECT
        'area_of_expertise' => 'array',
    ];
    protected $appends = ['encoded_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'unique_id', 'unique_id');
    }

    public function getEncodedIdAttribute()
    {
        return encrypt($this->id);
    }

    public function scopePhysician(Builder $query)
    {
        return $query->where('specialty', 'physician')->count();
    }

    public function scopeGyn(Builder $query)
    {
        return $query->where('specialty', 'gyn')->count();
    }

    public function scopeRequestsCount(Builder $query)
    {
        return $query->selectRaw('COUNT(*) as total_requests, SUM(CASE WHEN specialty = "physician" THEN 1 ELSE 0 END) as physicians_count, SUM(CASE WHEN specialty = "gyn" THEN 1 ELSE 0 END) as gyn_count');
    }

    // public function getAreaOfExpertiseAttribute()
    // {
    //     return $this->area_of_experties ? json_decode($this->area_of_experties ?? '[]', true) : [];
    // }

    /**
     * Helpers
     */
    public function PhysiciansGynCount()
    {
        return $this->physicians_count + $this->gyn_count;
    }

    public function IsEligible()
    {
        return ($this->physicians_count + $this->gyn_count) < 25;
    }
}
