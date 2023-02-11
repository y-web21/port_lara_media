<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class RedirectPagination
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        /**  @var Response */
        $response = $next($request);
        $this->RedirectNonExistentPageNumbersTo404($response);
        return $response;
    }

    /**
     * @param Response $response
     */
    private function RedirectNonExistentPageNumbersTo404($response): void
    {
        $paginator = $response->original->articles ?? null;
        if ($this->isPaginator($paginator)) {
            if ($paginator->currentPage() > $paginator->lastPage()) {
                abort(404);
            }
        } else {
            abort(404);
        }
    }
    private function isPaginator($paginator): bool
    {
        return $paginator instanceof LengthAwarePaginator;
    }

}
