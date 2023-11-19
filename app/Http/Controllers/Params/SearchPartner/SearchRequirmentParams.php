<?php
namespace App\Http\Controllers\Params\SearchPartner;

class SearchRequirmentParams
{

    public ?int $user_search_id;
    public ?int $requirment_id;
    public ?int $requirment_item_id;

    public function __construct(?int $user_search_id = null, ?int $requirment_id = null, ?int $requirment_item_id = null)
    {

        $this->user_search_id = $user_search_id;
        $this->requirment_id = $requirment_id;
        $this->requirment_item_id = $requirment_item_id;
    }

    public function toMap(): array
    {

        return [
            'user_search_id' => $this->user_search_id,
            'requirment_id' => $this->requirment_id,
            'requirment_item_id' => $this->requirment_item_id,
        ];
    }

}