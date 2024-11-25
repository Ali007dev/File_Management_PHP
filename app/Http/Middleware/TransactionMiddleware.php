<?php

namespace App\Http\Middleware;

use App\Exceptions\GeneralException;
use Closure;
use DB;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{
            DB::beginTransaction();
            $response =  $next($request);
        }catch(Exception $e){
            DB::rollBack();
            throw new GeneralException($e->getMessage(),500);
        }
        if($response instanceof Response && $response->getStatusCode() > 399){
            DB::rollBack();
        }else {
            DB::commit();
        }
        return $response;
    }
}
