<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LoanApplication;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $pendingLoans = LoanApplication::where('status', 'Pending')->count();
        $approvedLoans = LoanApplication::where('status', 'Approved')->count();
        $totalGroups = \App\Models\Group::count(); // kama una Model ya Group

        return view('admin.dashboard', compact('totalUsers', 'pendingLoans', 'approvedLoans', 'totalGroups'));
    }
}