<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FiltersController extends Controller
{
    public function getBranches(){
        return response()->json(DB::select('CALL getBranchesFilter()'));
    }
}
