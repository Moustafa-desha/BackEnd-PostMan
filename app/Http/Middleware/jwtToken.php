<?php

namespace App\Http\Middleware;


use App\Http\Traits\ApiDesignTrait;
use Closure;
use JWTAuth;
use Exception;
use Illuminate\Http\Request;



class jwtToken
{
    use ApiDesignTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            JWTAuth::parseToken()->authenticate();

        } catch (Exception $e) {
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return $this->ApiResponse(422,'This Token Expired');

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return $this->ApiResponse(422,'This Token Invalid');

            }else{
                return $this->ApiResponse(404,'Token Not Found');
            }
        }

        return $next($request);
    }
}
