<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */public function index()
    {
        $Notification = Notification::orderBy('id', 'DESC')->get();

        return response()->json([
            'success' => true,
            'data' => $Notification
        ], 200);

    }
    /**
     * Display a listing of items in store
     */public function storeNotification(string $id)
    {

        $Notification = Notification::where('recipient', '=', $id)->orderBy('id', 'DESC')->get();

        return response()->json([
            'success' => true,
            'data' => $Notification
        ], 200);

    }
    public function updateStatus(string $id)
    {
        $Notification = Notification::find($id);

        $Notification->status = "seen";
        $Notification->save();
    }


}