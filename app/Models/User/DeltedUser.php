<?php

namespace App\Models\User;

use App\Models\Color\Color;
use App\Models\EducationType\EducationType;
use App\Models\Location\Country\Country;
use App\Models\Location\State\State;
use App\Models\MaritalStatus\MaritalStatus;
use App\Models\MarriageReadiness\MarriageReadiness;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeltedUser extends Model
{
    use HasFactory;

    protected $table = "delted_users";
    protected $guarded = [];

    protected $appends = ["image_link"];

    public function getImageLinkAttribute()
    {
        return $this->image ? asset($this->image) : '';
    }


    public function delted_country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
    public function delted_state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function delted_marital_status(): BelongsTo
    {
        return $this->belongsTo(MaritalStatus::class);
    }

    public function delted_education_type(): BelongsTo
    {
        return $this->belongsTo(EducationType::class);
    }

    public function delted_color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function delted_marriage_readiness(): BelongsTo
    {
        return $this->belongsTo(MarriageReadiness::class);
    }

    public function delted_images(): HasMany
    {
        return $this->hasMany(UserImage::class);
    }
}
