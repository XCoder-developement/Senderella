<?php
namespace App\Http\Services;

use App\Models\UserSearch\UserSearch;
use App\Models\UserSearch\UserSearchRequirment;

class StoreSearchService
{
    public function storeUserSearch($request)
    {
        $store_time = 3;
        $searchData = array_filter([
            'user_id' => auth()->id(),
            'age_from' => $request->age_from ?? null,
            'age_to' => $request->age_to ?? null,
            'country_id' => $request->country_id ?? null,
            'state_id' => $request->state_id ?? null,
            'nationality_id' => $request->nationality_id ?? null,
            'marital_status_id' => $request->marital_status_id ?? null,
            'word' => $request->word ?? null,
            'height' => $request->height ?? null,
            'weight' => $request->weight ?? null,
        ]);
        $count = UserSearch::where("user_id", auth()->id())->count();
        if (!empty($searchData) && $count < $store_time) {
            $userSearch = UserSearch::create($searchData);
            $this->storeUserRequirment($request, $userSearch->id);
        } else {
            $userSearch = UserSearch::where("user_id", auth()->id())->delete();
            $stored = UserSearch::create($searchData);
            $this->storeUserRequirment($request, $stored->id);
        }
        return response();

    }
    public function storeUserRequirment($request, $userSearchId)
    {
        if (isset($request->user_info_data) && count($request->user_info_data) > 0) {
            foreach ($request->user_info_data as $data) {
                $store_data = [
                    'user_search_id' => $userSearchId,
                    'requirment_id' => $data['requirment_id'],
                    'requirment_item_id' => $data['requirment_item_id'],
                ];
                UserSearchRequirment::create($store_data);
            }
        }
    }
}