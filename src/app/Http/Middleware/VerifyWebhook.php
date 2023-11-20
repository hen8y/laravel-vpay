<?php

namespace Hen8y\Vpay\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use Ahc\Jwt\JWT;



class VerifyWebhook
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $secretKey =  Config::get("vpay.secret_key");
        $jwt = new JWT($secretKey);
        $token= $request->header("x-payload-auth");
        $secret = $jwt->decode($token)['secret'];

        if ($secret != $secretKey) {
            
            return response()->json(['error' => 'Invalid secret code'], 403);
        }
            return $next($request);
        }


}
