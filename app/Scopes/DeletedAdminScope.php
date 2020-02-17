<?php

namespace App\Scopes;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class DeletedAdminScope implements Scope{
    public function apply(Builder $builder, Model $model){
        if(Auth::check() && Auth::user()->is_admin){
            // $builder->withTrashed();
            $builder->withoutGlobalScope('Illuminate\Database\Eloquent\SoftDeletingScope');
        }
    }
}



?>