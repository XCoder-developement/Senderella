<?php
namespace App\Http\Controllers\Params\SearchPartner;

use Illuminate\Http\Client\Request;

class SearchPartnerParams
{
    public ?int $age_from;
    public ?int $age_to;
    public ?int $marital_status_id;
    public ?int $weight;
    public ?int $height;
    public ?int $state_id;
    public ?int $country_id;
    public ?int $nationality_id;
    public ?array $user_info_data;
    public ?string $word;

    public function __construct(
        ?int $age_from = null,
        ?int $age_to = null,
        ?int $marital_status_id = null,
        ?int $weight = null,
        ?int $height = null,
        ?int $state_id = null,
        ?int $country_id = null,
        ?int $nationality_id = null,
        ?array $user_info_data = null,
        ?string $word = null
    ) {
        $this->age_from = $age_from;
        $this->age_to = $age_to;
        $this->marital_status_id = $marital_status_id;
        $this->weight = $weight;
        $this->height = $height;
        $this->state_id = $state_id;
        $this->country_id = $country_id;
        $this->nationality_id = $nationality_id;
        $this->user_info_data = $user_info_data;
        $this->word = $word;
    }

    public function toMap(): array
    {
        $userInfoData = [];
        if ($this->user_info_data) {
            foreach ($this->user_info_data as $data) {
                $userInfoData[] = $data->toMap();
            }
        }
        return [
            'age_from' => $this->age_from,
            'age_to' => $this->age_to,
            'marital_status_id' => $this->marital_status_id,
            'weight' => $this->weight,
            'height' => $this->height,
            'state_id' => $this->state_id,
            'country_id' => $this->country_id,
            'nationality_id' => $this->nationality_id,
            'user_info_data' => $userInfoData ?? [],
            'word' => $this->word,
        ];
    }
    public static function buildBody($data): SearchPartnerParams
    {
        $requirements = [];
        foreach ($data['requirments'] as $requirement) {
            $requirements[] = new SearchRequirmentParams(
                user_search_id: $requirement['user_search_id'],
                requirment_id: $requirement['requirment_id'],
                requirment_item_id: $requirement['requirment_item_id'],
            );
        }
        return new SearchPartnerParams(
            age_from: $data['age_from'],
            age_to: $data['age_to'],
            marital_status_id: $data['marital_status_id'],
            weight: $data['weight'],
            height: $data['height'],
            state_id: $data['state_id'],
            country_id: $data['country_id'],
            nationality_id: $data['nationality_id'],
            user_info_data: $requirements,
            word: $data['word']
        );
    }
}
