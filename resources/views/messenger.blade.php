@extends('layouts.app')

@section('content')
<div class="container">
    <messenger-create-group v-bind:initial-users="{{ $users }}"></messenger-create-group>
    <hr>
    <div class="container">
        <messenger-groups v-bind:initial-groups="{{ $groups }}" v-bind:user="{{ $user }}"></messenger-groups>
    </div>
</div>
@endsection