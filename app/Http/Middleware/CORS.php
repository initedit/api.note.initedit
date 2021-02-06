<?php

namespace App\Http\Middleware;

use Closure;

class CORS
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
        // header("Access-Allow-Control-Origin: *");
        //Intercepts OPTIONS requests
        if ($request->isMethod('OPTIONS')) {
            $response = response('', 200);
        } else {
            // Pass the request to the next middleware
            $response = $next($request);
        }
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT,PATCH,DELETE");
        header("Access-Control-Allow-Headers: name,token,Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
        
        return $response;
        // $headers = [
        //     'Access-Control-Allow-Origin'      => '*',
        //     'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE, PATCH',
        //     'Access-Control-Allow-Credentials' => 'true',
        //     'Access-Control-Max-Age'           => '86400',
        //     'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With'
        // ];

        // if ($request->isMethod('OPTIONS'))

        // {
        //     return response()->json('{"method":"OPTIONS"}', 200, $headers);
        // }

        // $response = $next($request);
        // foreach($headers as $key => $value)
        // {
        //     $response->header($key, $value);
        // }

        // return $response;
    }
}
