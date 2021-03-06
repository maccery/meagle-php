@extends('layouts.app')
@section('content')
    <div class="content-row">
        <div class="container">
            <h1>Add a piece of software</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('browse') }}">Browse</a></li>
                <li class="active">Create software</li>
            </ul>
            <div class="alert alert-warning">
                <p>If you add a piece of software to the platform users will be able to review it. This is known as crowd-sourcing. You can read about how this works <a href="{{ route('process') }}">here</a>.</p>
            </div>
            <form method="POST" action="{{ route('post_create_software') }}">
                <div class="form-group">
                    <input class="form-control" name="name" class="input-group input-lg" placeholder="Enter software name here">
                </div>
                @if (Auth::guest())
                    <p><small><a href="{{ url('/register') }}">Register</a> to submit</small></p>
                @else
                    <p><small>Submit as <b>{{ Auth::user()->name }}</b></small></p>
                    <button class="btn btn-default">Create</button>
                @endif
                {{ csrf_field() }}
            </form>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @include('errors.generic')
        </div>
    </div>
@endsection