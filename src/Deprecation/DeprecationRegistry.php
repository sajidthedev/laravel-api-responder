<?php

declare(strict_types=1);

namespace ApiResponder\Deprecation;

final class DeprecationRegistry
{
    /** @var array<string, Deprecation> */
    private array $deprecations = [];

    /**
     * Register a deprecation.
     *
     * @param Deprecation $deprecation
     * @return void
     */
    public function register(Deprecation $deprecation): void
    {
        $this->deprecations[$deprecation->key] = $deprecation;
    }

    /**
     * Find a deprecation by route name or path.
     *
     * @param string|null $routeName
     * @param string $path
     * @return Deprecation|null
     */
    public function find(?string $routeName, string $path): ?Deprecation
    {
        // 1. Exact match by route name or exact path
        if ($routeName !== null && isset($this->deprecations[$routeName])) {
            return $this->deprecations[$routeName];
        }

        $normalizedPath = ltrim($path, '/');

        // 2. Wildcard matching
        foreach ($this->deprecations as $key => $deprecation) {
            if (str_contains($key, '*')) {
                $pattern = '/^' . str_replace('\*', '.*', preg_quote($key, '/')) . '$/i';
                if (preg_match($pattern, $normalizedPath)) {
                    return $deprecation;
                }
            }
        }

        return null;
    }
}
