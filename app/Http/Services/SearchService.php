<?php
namespace App\Http\Services;

use App\Models\User\User;

class SearchService
{
    public function search($search, $with_store = true)
    {
        $search = (object) $search;
        $query = User::where('id', '!=', auth()->id())
            ->where(function ($q) use ($search) {
                $q->when(isset($search->word), function ($q) use ($search) {
                    $q->orWhere('name', 'like', '%' . $search->word . '%');
                });
                $q->when(isset($search->state_id), function ($q) use ($search) {
                    $q->orwhere('state_id', $search->state_id);
                });
                $q->when(isset($search->country_id), function ($q) use ($search) {
                    $q->orwhere('country_id', $search->country_id);
                    $q->orwhere('country_id', $search->nationality_id);
                });
                $q->when(isset($search->weight), function ($q) use ($search) {
                    $q->orwhere('weight', $search->weight);
                });
                $q->when(isset($search->height), function ($q) use ($search) {
                    $q->orwhere('height', $search->height);
                });
                $q->when(isset($search->marital_status_id), function ($q) use ($search) {
                    $q->orwhere('marital_status_id', $search->marital_status_id);
                });
                $q->when(isset($search->age_from) && isset($search->age_to), function ($q) use ($search) {
                    $q->ageRange($search->age_from, $search->age_to);
                });
                $q->when(isset($search->user_info_data), function ($q) use ($search) {
                    foreach ($search->user_info_data as $data) {
                        $q->orWhereHas('informations', function ($subQuery) use ($data) {
                            $subQuery->where('requirment_id', $data['requirment_id'])
                                ->where('requirment_item_id', $data['requirment_item_id']);
                        });
                    }
                });
            });
        $user = $query->paginate(10);
        if ($with_store) {
            (new StoreSearchService())->storeUserSearch($search);
        }
        return $user;
    }

}