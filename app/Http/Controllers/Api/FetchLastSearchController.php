<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Params\SearchPartner\SearchPartnerParams;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\SearchService;
use App\Models\UserSearch\UserSearch;
use App\Http\Resources\Api\PartnerResource;
use App\Models\User\User;
use Illuminate\Support\Facades\Validator;

class FetchLastSearchController extends Controller
{
    use ApiTrait;
    public function fetch_last_search()
    {
        try {

            $user = auth()->id();
            $fetch_search = UserSearch::with('requirments')->where('user_id', $user)->latest()->first();
            if (!$fetch_search) {
                $msg = "message.there is no last search";
                $all_partners = User::whereIn('id', [])
                    ->orderByRaw("FIELD(id, " . implode(',', []) . ")")
                    ->paginate(10);
                $AllPartners = PartnerResource::collection( $all_partners)->response()->getData(true);
                return $this->dataResponse($msg, $AllPartners, 200);
            }

            $params = SearchPartnerParams::buildBody($fetch_search);
            $search = new SearchService();
            $partners = $search->search($params->toMap(), $with_store = false);
            $response = PartnerResource::collection($partners)->response()->getData(true);
            $i = count($response['data']);
            $userIds = []; // Initialize an array to store the IDs
            // dd($params);
            for ($j = 0; $j < $i; $j++) {
                $userIds[] = $response['data'][$j]['id']; // Extract the ID and add it to the $userIds array
            }
            $online_partners = User::whereIn('users.id', $userIds)
                ->whereHas('last_shows', function ($query) {
                    $query->where('status', 1);
                })
                ->orderBy('id', 'desc')
                ->pluck('id')
                ->toArray();
            // dd($online_partners);
            $offlines = User::whereIn('users.id', $userIds)
                ->whereHas('last_shows', function ($query) {
                    $query->where('status', 0);
                })
                ->orderByDesc(function ($query) {
                    $query->select('end_date')
                        ->from('user_last_shows')
                        ->whereColumn('user_id', 'users.id')
                        ->orderByDesc('end_date')
                        ->limit(1);
                })
                ->pluck('id')
                ->toArray();


            $all_partnersids = array_merge($online_partners, $offlines);

            $all_partners = User::whereIn('id', $all_partnersids)
                ->orderByRaw("FIELD(id, " . implode(',', $all_partnersids) . ")")
                ->paginate(10);

            $AllPartners = PartnerResource::collection($all_partners)->response()->getData(true);
            // dd($offlines);
            if ($fetch_search) {
                $msg = "fetch_last_search";
                return $this->dataResponse($msg, $AllPartners, 200);
            }

            // $msg = "fetch_last_search";
            // return $this->dataResponse($msg, $response, 200);$msg = "fetch_last_search";
            // return $this->dataResponse($msg, $response, 200);
        } catch (\Exception $e) {
            return $this->returnException($e->getMessage(), 500);
        }
    }
}
