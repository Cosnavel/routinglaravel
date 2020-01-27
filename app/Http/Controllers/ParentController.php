<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Mother;

class ParentController extends Controller
{
    public function createMother($user_id, $name)
    {
        User::findOrFail($user_id)->mother()->create(['name' => $name]);
    }
    public function createFather($user_id, $name)
    {
        User::findOrFail($user_id)->father()->create(['name' => $name]);
    }
    public function getUserRelationships($user_id)
    {
        $user_name = User::findOrFail($user_id)->name;
        $mother = User::findOrFail($user_id)->mother->name;
        $father = User::findOrFail($user_id)->father->name;

        return "Ich bin " . $user_name . ", meine Muttter heißt: " . $mother . " und mein Vater: " . $father;
    }
    public function createUser()
    {
        $user = new User;
        $user->name = "Niclas";
        $user->email = "test@test.de";
        $user->password = hash('md5', '12345');
        $user->save();
    }

    public function childOfMother($mother_id)
    {
        $user_name = Mother::find($mother_id)->user->name;
        $name = Mother::find($mother_id)->name;
        return "Ich, " . $name . "habe ein Kind namens " . $user_name;
    }
}
