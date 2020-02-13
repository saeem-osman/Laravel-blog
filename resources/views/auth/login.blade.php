@extends('layout')
@section('content')
    <form method="POST" action="{{route('login')}}">
        @csrf
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
            <div class="form-check">
            <input class="form-check-input" name="remember" type="checkbox" value="{{old('remember')? 'checked': ''}}" >
              <label class="form-check-label" for="invalidCheck2">
                Remember Me
              </label>
            </div>
          </div>
        <div class="form-group">
        <button type="submit" class="btn btn-primary btn-block">Login!!!</button>
        </div>
    </form>
@endsection