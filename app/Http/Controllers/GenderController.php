<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use Illuminate\Database\Eloquent\Collection;

class GenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Gender[]|Collection
     */
    public function index()
    {
        return Gender::all();
    }

}
