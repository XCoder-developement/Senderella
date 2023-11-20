<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\NotificationDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Notification\StoreRequest;
use App\Http\Requests\Admin\Notification\UpdateRequest;
use App\Models\Notification\Notification;
use App\Services\SendNotification;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class NotificationController extends Controller
{
    //
    protected $view = 'admin_dashboard.notifications.';
    protected $route = 'notifications.';

    public function index(NotificationDataTable $dataTable)
    {
        return $dataTable->render($this->view . 'index');

    }


    public function create()
    {
        return view($this->view . 'create');
        SendNotification::create(request()->all());

    }


    public function store(StoreRequest $request)
    {

        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode], 'body' => $request['body-' . $localeCode]
          ];
        }


        Notification::create($data);



        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    // public function edit($id)
    // {
    //     $notification = Notification::whereId($id)->first();

    //     return view($this->view . 'edit' , compact('notification'));

    // }


    public function update(UpdateRequest $request, $id)
    {
        $notification = Notification::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],  'body' => $request['body-' . $localeCode],
          ];
        }


        $notification->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $notification = Notification::whereId($id)->firstOrFail();
        $notification->delete();
        return response()->json(['status' => true]);
    }


}
