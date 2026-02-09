<?php

declare(strict_types=1);

namespace ApiResponder\ErrorCodes;

use ApiResponder\Contracts\ErrorCodeProvider;

final class ErrorCodeRegistry
{
    /** @var array<string, ErrorCode> */
    private array $errorCodes = [];

    /**
     * Register a single error code.
     *
     * @param ErrorCode $errorCode
     * @return void
     */
    public function register(ErrorCode $errorCode): void
    {
        $this->errorCodes[$errorCode->code] = $errorCode;
    }

    /**
     * Register multiple error codes.
     *
     * @param iterable $items
     * @return void
     */
    public function registerMany(iterable $items): void
    {
        foreach ($items as $item) {
            if ($item instanceof ErrorCode) {
                $this->register($item);
            }
        }
    }

    /**
     * Register error codes from a provider.
     *
     * @param ErrorCodeProvider $provider
     * @return void
     */
    public function registerProvider(ErrorCodeProvider $provider): void
    {
        foreach ($provider->all() as $code => $options) {
            $this->register(new ErrorCode(
                code: $code,
                defaultMessage: $options['message'] ?? null,
                defaultStatus: $options['status'] ?? 400
            ));
        }
    }

    /**
     * Check if an error code is registered.
     *
     * @param string $code
     * @return bool
     */
    public function has(string $code): bool
    {
        return isset($this->errorCodes[$code]);
    }

    /**
     * Get a registered error code.
     *
     * @param string $code
     * @return ErrorCode|null
     */
    public function get(string $code): ?ErrorCode
    {
        return $this->errorCodes[$code] ?? null;
    }
}
