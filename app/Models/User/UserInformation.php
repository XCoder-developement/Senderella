<?php

namespace App\Models\User;

use App\Models\Color\Color;
use App\Models\EducationType\EducationType;
use App\Models\EleganceStyle\EleganceStyle;
use App\Models\EyeColor\EyeColor;
use App\Models\FamilyValue\FamilyValue;
use App\Models\FirstMeet\FirstMeet;
use App\Models\HairColor\HairColor;
use App\Models\HealthStatus\HealthStatus;
use App\Models\HijibType\HijibType;
use App\Models\MaritalStatus\MaritalStatus;
use App\Models\MarriageReadiness\MarriageReadiness;
use App\Models\MovingPlace\MovingPlace;
use App\Models\MultiplicityStatus\MultiplicityStatus;
use App\Models\Procreation\Procreation;
use App\Models\Religiosity\Religiosity;
use App\Models\Requirment\Requirment;
use App\Models\RequirmentItem\RequirmentItem;
use App\Models\WorkType\WorkType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInformation extends Model
{
    use HasFactory;
    protected $table = 'user_informations';
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function requirment(): BelongsTo
    {
        return $this->belongsTo(Requirment::class);
    }

    public function requirment_item(): BelongsTo
    {
        return $this->belongsTo(RequirmentItem::class);
    }

}
