<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Params\SearchPartner\SearchPartnerParams;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\SearchService;
use App\Models\UserSearch\UserSearch;
use App\Http\Resources\Api\PartnerResource;

class FetchLastSearchController extends Controller
{
    use ApiTrait;
    public function fetch_last_search()
    {
        try {
            $user = auth()->id();
            $fetch_search = UserSearch::with('requirments')
            ->whereHas('lastShow', function ($query) {
                $query->where('status', 1);
            })
            ->where('user_id', $user)->latest()->first();
            if (!$fetch_search) {
                $msg = "message.there is no last search";
                return $this->dataResponse($msg, [] ,200);
            }

            $params = SearchPartnerParams::buildBody($fetch_search);
            $search = new SearchService();
            $partners = $search->search($params->toMap(), $with_store = false);
            $response = PartnerResource::collection($partners)->response()->getData(true);
            if($fetch_search){
                $msg = "fetch_last_search";
                return $this->dataResponse($msg,$response['data'], 200);

            }

            // $msg = "fetch_last_search";
            // return $this->dataResponse($msg, $response, 200);$msg = "fetch_last_search";
            // return $this->dataResponse($msg, $response, 200);
        } catch (\Exception $e) {
            return $this->returnException($e->getMessage(), 500);
        }
    }
}
