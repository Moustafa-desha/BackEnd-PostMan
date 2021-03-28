<?php

namespace App\Http\Middleware;


use App\Http\Traits\ApiDesignTrait;
use Closure;
use Illuminate\Http\Request;




class Roles
{
    use ApiDesignTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
   /*  */
    public function handle(Request $request, Closure $next, $roles)
    {
        $userRole = auth()->user()->roleName->name;

//        here we will receive $roles and convert to array and compare between $userRole and $allowedUser
        $allowUser = explode ('.',$roles);

        if(!in_array($userRole , $allowUser)){
            return $this->ApiResponse(422,' OOPS Not Allow for you not your Job');
        }
        return $next($request);
    }

}
