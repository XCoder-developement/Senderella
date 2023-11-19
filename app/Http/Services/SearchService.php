<?php
namespace App\Http\Services;

use App\Models\User\User;

class SearchService
{
    public static function search($search)
    {
        return User::where('id', '!=', auth()->id())
            ->where(function ($q) use ($search) {
                $q->when($search->has('word'), function ($q) use ($search) {
                    $q->orWhere('name', 'like', '%' . $search->word . '%');
                });
                $q->when($search->has('state_id') && $search->state_id, function ($q) use ($search) {
                    $q->orwhere('state_id', $search->state_id);
                });
                $q->when($search->has('country_id'), function ($q) use ($search) {
                    $q->orwhere('country_id', $search->country_id);
                    $q->orwhere('country_id', $search->nationality_id);
                });
                $q->when($search->has('weight'), function ($q) use ($search) {
                    $q->orwhere('weight', $search->weight);
                });
                $q->when($search->has('height'), function ($q) use ($search) {
                    $q->orwhere('height', $search->height);
                });
                $q->when($search->has('marital_status_id'), function ($q) use ($search) {
                    $q->orwhere('marital_status_id', $search->marital_status_id);
                });
                $q->when($search->has('age_from') && $search->has('age_to'), function ($q) use ($search) {
                    $q->ageRange($search->age_from, $search->age_to);
                });

                $q->when($search->has('user_info_data'), function ($q) use ($search) {
                    foreach ($search->user_info_data as $data) {
                        $q->orWhereHas('informations', function ($subQuery) use ($data) {
                            $subQuery->where('requirment_id', $data['requirment_id'])
                                ->where('requirment_item_id', $data['requirment_item_id']);
                        });
                    }
                });
            })->paginate(10);
    }
}