<?php

declare(strict_types=1);

namespace ApiResponder\Tests\Deprecation;

use ApiResponder\Deprecation\Deprecation;
use ApiResponder\Deprecation\DeprecationRegistry;
use ApiResponder\Tests\TestCase;

class DeprecationRegistryTest extends TestCase
{
    private DeprecationRegistry $registry;

    protected function setUp(): void
    {
        parent::setUp();
        $this->registry = new DeprecationRegistry();
    }

    public function test_it_matches_exact_route_name(): void
    {
        $deprecation = new Deprecation('users.index');
        $this->registry->register($deprecation);

        $this->assertSame($deprecation, $this->registry->find('users.index', 'api/users'));
    }

    public function test_it_matches_wildcard_paths(): void
    {
        $deprecation = new Deprecation('api/v1/*');
        $this->registry->register($deprecation);

        $this->assertSame($deprecation, $this->registry->find(null, 'api/v1/users'));
        $this->assertSame($deprecation, $this->registry->find(null, '/api/v1/orders/1'));
        $this->assertNull($this->registry->find(null, 'api/v2/users'));
    }

    public function test_it_prefers_exact_route_name_over_wildcard_match(): void
    {
        $wildcard = new Deprecation('api/*');
        $exact = new Deprecation('users.index');

        $this->registry->register($wildcard);
        $this->registry->register($exact);

        // find(routeName, path)
        $result = $this->registry->find('users.index', 'api/users');

        $this->assertSame($exact, $result);
        $this->assertNotSame($wildcard, $result);
    }
}
