<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::all();

        return view('notifications.index', compact('notifications'));
    }

    public function create()
    {
        return view('notifications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'time' => 'required',
            'message' => 'required',
            'type' => 'required',
            'itemid' => 'required',
            'recipient' => 'required',
            'status' => 'required'
        ]);

        Notification::create($request->all());

        return redirect()->route('notifications.index')
            ->with('success', 'Notification created successfully.');
    }

    public function edit(Notification $notification)
    {
        return view('notifications.edit', compact('notification'));
    }

    public function update(Request $request, Notification $notification)
    {
        $request->validate([
            'title' => 'required',
            'time' => 'required',
            'message' => 'required',
            'type' => 'required',
            'itemid' => 'required',
            'recipient' => 'required',
            'status' => 'required'
        ]);

        $notification->update($request->all());

        return redirect()->route('notifications.index')
            ->with('success', 'Notification updated successfully.');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'Notification deleted successfully.');
    }
}