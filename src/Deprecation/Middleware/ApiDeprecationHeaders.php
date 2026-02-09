<?php

declare(strict_types=1);

namespace ApiResponder\Deprecation\Middleware;

use ApiResponder\Deprecation\DeprecationRegistry;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ApiDeprecationHeaders
{
    public function __construct(
        private readonly DeprecationRegistry $registry
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $deprecation = $this->registry->find(
            $request->route()?->getName(),
            $request->getPathInfo()
        );

        if ($deprecation) {
            $response->headers->set('Deprecation', 'true');

            if ($deprecation->sunsetAt) {
                $response->headers->set('Sunset', $deprecation->sunsetAt->format(\DateTimeInterface::RFC1123));
            }

            if ($deprecation->link) {
                $response->headers->set('Link', sprintf('<%s>; rel="deprecation"', $deprecation->link));
            }

            if ($deprecation->message) {
                $response->headers->set('X-API-Deprecation-Message', $deprecation->message);
            }
        }

        return $response;
    }
}
