<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'title' => 'Dashboard E-SCM Marmer Tulungagung',
            'cluster' => 'UD Cahaya Onix & UD Putra Abadi',
            'status' => 'initialized'
        ]);
    }
}
