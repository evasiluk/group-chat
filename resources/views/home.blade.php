@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <create-group v-bind:initial-users="{{ $users }}"></create-group>

        <div class="col-sm-6">
            <groups v-bind:initial-groups="{{ $groups }}" v-bind:user="{{ $user }}"></groups>
        </div>
    </div>
</div>
@endsection