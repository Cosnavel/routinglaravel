<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;



class TestController extends Controller
{
    //




    public function printMessage()
    {
        return "Hallo Welt wie geht es dir?";
    }
    public function showName($name, $nachname)
    {
        return "der übergebene Name lautet:" . ' ' . $name . ' ' . $nachname;
    }
    public function showUsername($username = null)
    {
        return "Der username = " . $username;
    }
}
