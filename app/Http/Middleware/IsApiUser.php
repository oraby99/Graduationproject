<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class IsApiUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->has('access_token'))
         {
            if($request->access_token !== null)
            {
                $user = User::where('access_token', $request->access_token)->first();
                if($user !== null)
                {
                return $next($request);
                }
                else
                {
                return response()->json( [
                    'status'  => '404',
                    "message" => "This Access Token Is Not Correct",
                    'data'   => NULL

                ]);
                }
            }
            else
            {
            return response()->json([
                'status'  => '404',
                "message" => "This Access Token Is Empty",
                'data'   => NULL

            ]);
            }
         }
         else
         {
         return response()->json([
            'status'  => '404',
            "message" => "There Is No Access Token ",
            'data'   => NULL

         ]);
         }
    }
}
