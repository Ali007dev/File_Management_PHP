<?php

namespace App\Http\Middleware;

use App\Exceptions\GeneralException;
use Closure;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
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
            FacadesDB::beginTransaction();
            $response =  $next($request);
        }catch(Exception $e){
            FacadesDB::rollBack();
            throw new GeneralException($e->getMessage(),500);
        }
        if($response instanceof Response && $response->getStatusCode() > 399){
            FacadesDB::rollBack();
        }else {
            FacadesDB::commit();
        }
        return $response;
    }
}
