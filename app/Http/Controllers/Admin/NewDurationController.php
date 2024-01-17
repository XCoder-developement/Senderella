<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewDuration\DurationRequest;
use App\Models\NewDuration\NewDuration;
use Illuminate\Http\Request;

class NewDurationController extends Controller
{
    //
    protected $view = 'admin_dashboard.new_durations.';
    protected $route = 'new_durations.';

    public function index()
    {
        $duration = NewDuration::firstOrNew();
        return view($this->view . 'index', compact('duration'));
    }

    public function update(DurationRequest $request)
    {
        $duration = NewDuration::firstOrNew();
        $duration->new_duration = $request->new_duration;
        if ($duration->exists) {
            $duration->update();
        } else {
            $duration->save();
        }
        return redirect()->back();
    }
}
