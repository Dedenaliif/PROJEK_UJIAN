<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HalamanMonitoringController extends Controller
{
    public function index(){
        return view('ujian.halaman-monitoring');
    }
}
