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
            $partner = User::where('country_id', 'like', '%', $request->country_id)
                ->orwhere('state_id', 'like', '%', $request->state_id)
                ->orwhere('country_id', 'like', '%', $request->nationality_id)
                ->orwhere('height', 'like', '%', $request->height)
                ->orwhere('weight', 'like', '%', $request->weight)
                ->orwhere('name', '%', 'like', '%', $request->word)
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
// $flight_type = $request->flight_type;
//         $trip_num = $request->trip_num;
//         $reservation_num = $request->reservation_num;
//         $reservation_status = $request->reservation_status;
//         $flight_status = $request->flight_status;

//         $query = Flight::with('trips', 'consumer', 'clients')->latest();

//         $query->when($flight_type, function ($query, $flight_type) {
//             $query->where('flight_type', $flight_type);
//         });
//         $query->when($reservation_status, function ($query, $reservation_status) {
//             $query->where('reservation_status', $reservation_status);
//         });
//         $query->when($flight_status, function ($query, $flight_status) {
//             $query->where('flight_status', $flight_status);
//         });

//         $query->when($trip_num, function ($query, $trip_num) {
//             $query->where(function ($query) use ($trip_num) {
//                 $query->where('trip_num', $trip_num)
//                     ->orWhere('reservation_num', $trip_num);
//             });
//         });