<?php

namespace App\Http\Controllers\Api;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PartnerResource;

class SearchPartnerController extends Controller
{
    public function search_partner(Request $request)
    {
        try {
            $partner = User::where('country_id', $request->country_id)
                ->orwhere('state_id', $request->state_id)
                ->orwhere('country_id', $request->nationality_id)
                ->orwhere('height', $request->height)
                ->orwhere('weight', $request->weight)
                ->orWhere('name', 'like', '%' . $request->word . '%')
                ->orwhere(function ($query) use ($request) {
                    $query->where('user_age', '>=', $request->age_to)->where('user_age', '<=', $request->age);
                })
                // ->orwhere('marital_status_id', 'like', '%', $request->marital_status_id)
                // ->orwhere('user_info_data', 'like', '%', $request->user_info_data)
                ->paginate(10);

            $res = PartnerResource::collection($partner)->response()->getData(true);
            $msg = 'fetch_partners';
            return $this->dataResponse($msg, $res, 200);
        } catch (\Exception $e) {
            return $this->returnException($e->getMessage(), 500);
        }
    }
}
