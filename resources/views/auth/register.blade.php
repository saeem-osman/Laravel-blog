@extends('layout')
@section('content')
    <form method="POST" action="{{route('register')}}">
        @csrf
        <div class="form-group">
            <label>Name</label>
        <input name="name" value="{{old('name')}}" required class="form-control {{$errors->has('name') ? ' is-invalid' : ''}}">
        @if ($errors->has('name'))
            <span class="invalid-feedback">
                {{$errors->first('name')}}
            </span>
        @endif
        </div>
        <div class="form-group">
            <label>E-mail</label>
        <input name="email" value="{{old('email')}}" required class="form-control {{$errors->has('email') ? ' is-invalid' : ''}}">
        @if ($errors->has('email'))
        <span class="invalid-feedback">
            {{$errors->first('email')}}
        </span>
        @endif
        </div>
        <div class="form-group">
            <label>Password</label>
            <input name="password" type="password" required class="form-control {{$errors->has('password') ? ' is-invalid' : ''}}">
            @if ($errors->has('password'))
                <span class="invalid-feedback">
                    {{$errors->first('password')}}
                </span>
            @endif
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" required class="form-control">
        </div>
        <button type="submit" class="btn btn-primary btn-block">Register!!!</button>
    </form>
@endsection