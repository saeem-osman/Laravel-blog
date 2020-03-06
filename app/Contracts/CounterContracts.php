<?php

namespace App\Contracts;

interface CounterContracts
{
    public function increments(string $key, array $tags=null):int;
}