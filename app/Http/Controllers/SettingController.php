<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index()
    {
        $settings = DB::table('settings')->get();

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->settings as $key => $value) {

            // Si es archivo (logo)
            if ($request->hasFile("settings.$key")) {

                $file = $request->file("settings.$key");

                $path = $file->store('settings', 'public');

                DB::table('settings')
                    ->where('key', $key)
                    ->update([
                        'value' => $path,
                        'updated_at' => now()
                    ]);

            } else {

                DB::table('settings')
                    ->where('key', $key)
                    ->update([
                        'value' => $value,
                        'updated_at' => now()
                    ]);
            }
        }

        return back()->with('success', 'Configuración actualizada');
    }
}