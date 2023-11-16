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
            $partner = User::where('id', '!=', auth()->id())
                ->where(function ($q) use ($request) {
                    $q->when($request->has('word'), function ($q) use ($request) {
                        $q->orWhere('name', 'like', '%' . $request->word . '%');
                    });
                    $q->when($request->has('state_id') && $request->state_id, function ($q) use ($request) {
                        $q->orwhere('state_id', $request->state_id);
                    });
                    $q->when($request->has('country_id'), function ($q) use ($request) {
                        $q->orwhere('country_id', $request->country_id);
                        $q->orwhere('country_id', $request->nationality_id);
                    });
                    $q->when($request->has('weight'), function ($q) use ($request) {
                        $q->orwhere('weight', $request->weight);
                    });
                    $q->when($request->has('height'), function ($q) use ($request) {
                        $q->orwhere('height', $request->height);
                    });
                    $q->when($request->has('marital_status_id'), function ($q) use ($request) {
                        $q->orwhere('marital_status_id', $request->marital_status_id);
                    });
                    $q->when($request->has('age_from') && $request->has('age_to'), function ($q) use ($request) {
                        $q->ageRange($request->age_from, $request->age_to);
                    });
                    $q->when($request->has('user_info_data'), function ($q) use ($request) {
                        $q->orWhereHas('informations', function ($subQuery) use ($request) {
                            foreach ($request->user_info_data as $key => $value) {
                                $subQuery->where($key, $value);
                            }
                        });
                    });
                })->paginate(10);
            $res = UserResource::collection($partner)->response()->getData(true);
            $msg = "search_partner";
            return $this->dataResponse($msg, $res, 200);
        } catch (\Exception $e) {
            return $this->returnException($e->getMessage(), 500);
        }
    }
    // try {
    //     $partner = User::where(function ($q) use ($request) {
    //         $q->orwhere('state_id', $request->has('state_id'))
    //             ->orWhere('country_id', $request->country_id)
    //             ->orWhere('country_id', $request->nationality_id)
    //             ->orWhere('height', $request->height)
    //             ->orWhere('weight', $request->weight)
    //             ->orWhere('marital_status_id', $request->marital_status_id)
    //             ->orwhere('name', $request->word)
    //             ->ageRange($request->age_from, $request->age_to);
    //     })->paginate(10);
    //     $res = UserResource::collection($partner);
    //     $msg = "search_partner";
    //     return $this->dataResponse($msg, $res, 200);
    // } catch (\Exception $e) {
    //     return $this->returnException($e->getMessage(), 500);
    // }
}
