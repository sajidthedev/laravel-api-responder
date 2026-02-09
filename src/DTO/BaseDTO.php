<?php

declare(strict_types=1);

namespace ApiResponder\DTO;

use Illuminate\Database\Eloquent\Model;

abstract class BaseDTO
{
    /**
     * Create a DTO instance from an associative array.
     */
    abstract public static function fromArray(array $data): static;

    /**
     * Create a DTO instance from a Laravel Model.
     */
    abstract public static function fromModel(Model $model): static;

    /**
     * Convert the DTO instance to an array.
     */
    abstract public function toArray(): array;
}
