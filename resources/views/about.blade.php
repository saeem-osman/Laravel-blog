@extends('layout')
@section('content')
    <h1>This is about page</h1>
    <h3>Contact us here</h3>
    @can('home.secret')
        <h2>Special Section</h2>
        <p>You are an admin. That's why you deserve everything. I like it.
        <a href=" {{route('secret')}}">Secret Link </a>  
    @endcan
@endsection