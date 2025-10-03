<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('siswa.dashboard');
    }

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    public function siswaDashboard()
    {
        return view('siswa.dashboard');
    }
}
