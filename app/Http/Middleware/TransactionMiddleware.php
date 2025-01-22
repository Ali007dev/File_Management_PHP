<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\GeneralException;

class TransactionMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $this->beginTransaction();
            $response = $next($request);
            $this->finalizeTransaction($response);
        } catch (Exception $e) {
            $this->rollBackTransaction();
            throw new GeneralException($e->getMessage(), 500);
        }
        return $response;
    }

    /**
     * Begin a new database transaction.
     */
    protected function beginTransaction()
    {
        FacadesDB::beginTransaction();
    }

    /**
     * Commit or roll back the transaction based on the response status.
     *
     * @param Response $response
     */
    protected function finalizeTransaction(Response $response)
    {
        if ($response instanceof Response && $response->getStatusCode() > 399) {
            $this->rollBackTransaction();
        } else {
            $this->commitTransaction();
        }
    }

    /**
     * Commit the database transaction.
     */
    protected function commitTransaction()
    {
        FacadesDB::commit();
    }

    /**
     * Roll back the database transaction.
     */
    protected function rollBackTransaction()
    {
        FacadesDB::rollBack();
    }
}
