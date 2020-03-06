@extends('layout')

@section('content')
    <form method="POST" enctype="multipart/form-data" class="form-horizontal"
        action="{{ route('users.update',['user' => $user->id]) }}">
        @csrf
        @method("PUT")
        <div class="row">
            <div class="col-4">
                <img src="{{$user->image ? $user->image->url() : ''}}" class="img-thumbnail avatar" />

                <div class="card mt-4">
                    <div class="card-body">
                        <h6>Upload a different photo</h6>
                        <input class="form-control-file" type="file" name="avatar" />
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                <label>{{__('Name:')}}</label>
                <input class="form-control" value="{{$user->name}}" type="text" name="name" />
                </div>
                <div class="form-group">
                    <label>{{__('Language:')}}</label>
                    <select class="form-control" name="locale">
                        @forelse (App\User::LOCALES as $locale => $label)
                            <option value="{{$locale}}" {{$user->locale != $locale ? : 'selected'}}>
                                {{$label}}
                            </option>
                        @empty
                            
                        @endforelse
                    </select>
                </div>
                @errors @enderrors

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Save Changes" />
                </div>
            </div>
        </div>
    </form>
@endsection