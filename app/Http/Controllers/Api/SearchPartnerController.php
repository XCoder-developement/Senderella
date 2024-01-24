<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiTrait;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\SearchService;
use App\Models\UserSearch\UserSearch;
use App\Http\Services\StoreSearchService;
use App\Http\Resources\Api\PartnerResource;

class SearchPartnerController extends Controller
{
    use ApiTrait;
    public function search_partner(Request $request)
    {
        try {
            $search = new SearchService();
            $partners = $search->search($request);
            $response = PartnerResource::collection($partners)->response()->getData(true);

            $msg = "search_partners";
            return $this->dataResponse($msg, $response, 200);
        } catch (\Exception $e) {
            return $this->returnException($e->getMessage(), 500);
        }
    }

}
