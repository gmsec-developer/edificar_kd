<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = \Spatie\Activitylog\Models\Activity::with('causer')->latest();

        if ($request->filled('user')) {
            $query->where('causer_id', $request->user);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $logs = $query->paginate(15);

        $users = \App\Models\User::pluck('name', 'id');

        return view('activity.index', compact('logs', 'users'));
    }
}