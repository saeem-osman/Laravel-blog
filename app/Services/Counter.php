<?php

namespace App\Services;

use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Contracts\Session\Session;
use App\Contracts\CounterContracts;

class Counter implements CounterContracts{
    private $timeout;
    private $cache;
    private $session;
    private $supportTags;

    public function __construct(Cache $cache, Session $session, int $timeout){
        $this->timeout = $timeout;
        $this->cache = $cache;
        $this->session = $session;
        $this->supportTags = method_exists($cache, 'tags');
    }
    public function increments(string $key, array $tags=null):int{
        // dump($this->cache);
        // dd($this->session);
        $sessionId = $this->session->getId();
        $counterKey = "{$key}-counter";
        $userKey = "{$key}-users";

        $cache = $this->supportTags && null!== $tags ? $this->cache->tags($tags) : $this->cache;

        // $users = Cache::tags(['blog-posts'])->get($userKey, []);
        $users = $cache->get($userKey, []);
        $userUpdate = [];
        $difference = 0;
        $now = now();

        foreach($users as $session => $lastVisit){
            if($now->diffInMinutes($lastVisit) >= $this->timeout){
                $difference--;
            }else{
                $usersUpdate[$session] = $lastVisit;
            }
        }

        if(!array_key_exists($sessionId,$users)
            || $now->diffInMinutes($users[$sessionId]) >= $this->timeout
        ){
            $difference++;
        }
        
        $usersUpdate[$sessionId] = $now;
        $cache->forever($userKey, $userUpdate);
        
        if(!$cache->has($counterKey)){
            $cache->forever($counterKey,1);
        }else{
            $cache->increment($counterKey, $difference);
        }

        $counter = $cache->get($counterKey);
        return $counter;
    }
}