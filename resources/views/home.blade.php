@extends('layout')
@section('content')
    <h1>{{__('messages.welcome') }}</h1>
    {{-- <h1>@lang('messages.welcome')</h1> --}}
    <p>{{__('messages.example_with_value',['name' => 'saeem'])}}</p>

    <p>{{ trans_choice('messages.plural', 0, ['a' => 'boss'])}} </p>
    <p>{{ trans_choice('messages.plural', 1, ['a' => 'is'])}} </p>

    <p>{{ trans_choice('messages.plural', 100, ['a' => 'great'])}} </p>

    <p>Using Json: {{__('This is home page.') }} </p>
    <p>Another ex: {{__('hello :name', ['name' =>'saeem']) }}



@endsection