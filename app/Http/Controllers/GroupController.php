<?php

namespace App\Http\Controllers;

use App\Events\GroupCreated;
use Illuminate\Http\Request;
use App\Group;

class GroupController extends Controller
{
    public function store()
    {
        $group = Group::create(["name" => request('name')]);

        $users = collect(request('users'));
        $users->push(auth()->id());

        $group->users()->attach($users);

        broadcast(new GroupCreated($group))->toOthers();

        return $group;
    }
}
