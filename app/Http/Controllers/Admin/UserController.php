<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\UserDataTable;
use App\Http\Controllers\Controller;
use App\Models\User\DeltedUser;
use App\Models\User\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $view = 'admin_dashboard.users.';
    protected $route = 'users.';


    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render($this->view . 'index');
    }

    public function destroy($id)
    {
        $user = User::whereId($id)->first();

        DeltedUser::create([
            'name'    => $user->name,
            'email'   => $user->email,
            'phone'   => $user->phone,
            'birthday_date' => $user->birthday_date,
            'country_id'    => $user->country_id,
            'nationality_id'    => $user->nationality_id,
            'state_id'      => $user->state_id,
            'marital_status_id' => $user->marital_status_id,
            'marriage_readiness_id' => $user->marriage_readiness_id,
            'color_id'      => $user->color_id,
            'education_type_id' => $user->education_type_id,
            'weight'        => $user->weight,
            'height'        => $user->height,
            'trusted'       => $user->trusted,
            'is_verify'     => $user->is_verify,
            'notes'         => $user->notes,
            'about_me'      => $user->about_me,
            'important_for_marriage' => $user->important_for_marriage,
            'is_married_before' => $user->is_married_before,
            'image'         => $user->image,
            'partner_specifications' => $user->partner_specifications,
            'gender'        => $user->gender,
        ]);

        delete_image($user->image);

        $user->delete();
        return response()->json(['status' => true]);
    }

    public function active($id)
    {
        //activate the user
        $user = User::whereId($id)->first();
        if ($user->trusted == 0) {
            $user->update(['trusted' => 1]);
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }
}
