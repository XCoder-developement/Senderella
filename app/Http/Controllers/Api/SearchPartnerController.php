<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\UserResource;
use App\Models\User\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PartnerResource;

class SearchPartnerController extends Controller
{
    use ApiTrait;
    public function search_partner(Request $request)
    {
        try {
            $partner = User::where(function ($q) use ($request) {
                $q->orwhere('state_id', $request->has('state_id'))
                    ->orWhere('country_id', $request->country_id)
                    ->orWhere('country_id', $request->nationality_id)
                    ->orWhere('height', $request->height)
                    ->orWhere('weight', $request->weight)
                    ->orWhere('marital_status_id', $request->marital_status_id)
                    ->orwhere('name', $request->word)
                    ->ageRange($request->age_from, $request->age_to);
            })->paginate(10);
            $res = UserResource::collection($partner);
            $msg = "search_partner";
            return $this->dataResponse($msg, $res, 200);
        } catch (\Exception $e) {
            return $this->returnException($e->getMessage(), 500);
        }
        // try {
        //     $query = User::query();

        //     if ($request->has('state_id')) {
        //         $query->orWhere('state_id', $request->state_id);
        //     }

        //     if ($request->has('country_id')) {
        //         $query->orWhere('country_id', $request->country_id);
        //     }

        //     if ($request->has('nationality_id')) {
        //         $query->orWhere('nationality_id', $request->nationality_id);
        //     }

        //     if ($request->has('height')) {
        //         $query->orWhere('height', $request->height);
        //     }

        //     if ($request->has('weight')) {
        //         $query->orWhere('weight', $request->weight);
        //     }

        //     if ($request->has('marital_status_id')) {
        //         $query->orWhere('marital_status_id', $request->marital_status_id);
        //     }

        //     if ($request->has('word')) {
        //         $query->orWhere('name', 'like', '%' . $request->word . '%');
        //     }

        //     if ($request->has('age_from') && $request->has('age_to')) {
        //         $query->ageRange($request->age_from, $request->age_to);
        //     }

        //     $partner = $query->paginate(10);
        //     $res = UserResource::collection($partner);
        //     $msg = "filter_data";
        //     return $this->dataResponse($msg, $res, 200);
        // } catch (\Exception $e) {
        //     return $this->returnException($e->getMessage(), 500);
        // }

    }

}
