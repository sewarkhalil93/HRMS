<?php

namespace App\Models;

use App\Traits\CreatedUpdatedDeletedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use CreatedUpdatedDeletedBy, HasFactory, SoftDeletes;

    protected $fillable = [
        'contract_id',
        'first_name',
        'father_name',
        'last_name',
        'mother_name',
        'birth_and_place',
        'national_number',
        'mobile_number',
        'degree',
        'gender',
        'address',
        'notes',
        'max_leave_allowed',
        'delay_counter',
        'hourly_counter',
        'is_active',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function fingerprints(): HasMany
    {
        return $this->hasMany(Fingerprint::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }

    public function leaves(): BelongsToMany
    {
        return $this->belongsToMany(Leave::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    // Computed Attribute - Start
    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }
    // Computed Attribute - End

    // Functions - Start
    public function getCurrentPositionAttribute()
    {
        $data = Timeline::with('position')->where('employee_id', $this->id)->whereNull('end_date')->first();
        if ($data) {
            return $data->position->name;
        } else {
            return 'Out of work';
        }
    }

    public function getEmployeePhoto()
    {
        $defaultPhotoName = 'profile-photos/.default-photo.jpg';
        $data = User::where('employee_id', $this->id)->first();

        if ($data) {
            return 'storage/'.$data->profile_photo_path;
        }

        return 'storage/'.$defaultPhotoName;
    }
    // Functions - End
}
