<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Group;
use App\User;

class MessengerController extends Controller
{
    public function index()
    {
        $groups = auth()->user()->groups;
        $users = User::where('id', '<>', auth()->user()->id)->get();
        $user = auth()->user();

        return view('messenger', ['groups' => $groups, 'users' => $users, 'user' => $user]);
    }
}
