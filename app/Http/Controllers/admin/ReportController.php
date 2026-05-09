<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Facture;

class ReportController extends Controller
{
    public function index()
    {
        $factures = Facture::all();

        return view('admin.factures', compact('factures'));
    }
}