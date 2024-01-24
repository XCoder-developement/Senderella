<?php
namespace App\Http\Services;

use App\Models\User\User;
use Carbon\Carbon;

class SearchService
{
    public function search($search, $with_store = true)
    {
        $search = (object) $search;
        // $users = User::where('birthday_date' , '>=', Carbon::now()->subYears($search->age_to))->get();
        // dd($users);

        $query = User::where('id', '!=', auth()->id())
            // ->where(function ($q) use ($search) {
                ->when($search->word, function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search->word . '%');
                })
                ->when($search->state_id, function ($q) use ($search) {
                    $q->where('state_id', $search->state_id);
                })
                ->when($search->country_id, function ($q) use ($search) {
                    $q->where('country_id', $search->country_id);
                    $q->where('country_id', $search->nationality_id);
                })->when($search->weight, function ($q) use ($search) {
                    $q->where('weight', $search->weight);
                })->when($search->height, function ($q) use ($search) {
                    $q->where('height', $search->height);
                })->when($search->marital_status_id, function ($q) use ($search) {
                    $q->where('marital_status_id', $search->marital_status_id);
                })->when($search->age_from && $search->age_to, function ($q) use ($search) {
                    $q->where('birthday_date', '>=', Carbon::now()->subYears($search->age_to))
                      ->where('birthday_date', '<=', Carbon::now()->subYears($search->age_from));
                })->when($search->user_info_data, function ($q) use ($search) {
                    foreach ($search->user_info_data as $data) {
                        $q->whereHas('informations', function ($subQuery) use ($data) {
                            $subQuery->where('requirment_id', $data['requirment_id'])
                                ->where('requirment_item_id', $data['requirment_item_id']);
                        });
                    }
                });
            // });

        $user = $query->paginate(10);
        if ($with_store) {
            (new StoreSearchService())->storeUserSearch($search);
        }
        return $user;
    }

}
