<?php

namespace App\Http\Controllers;


use App\Models\TrueSite;
use Discord\Discord;

class HomeController extends Controller
{

    /**
     * @throws \Discord\Exceptions\IntentException
     */
    public function index()
    {
        TrueSite::getStat(TrueSite::ROUTE_PVP, 'Скеил');


    }

}
