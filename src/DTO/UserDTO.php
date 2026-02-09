<?php

declare(strict_types=1);

namespace ApiResponder\DTO;

use Illuminate\Database\Eloquent\Model;

class UserDTO extends BaseDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $email,
        public readonly string $name,
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new static(
            id: $data['id'],
            email: $data['email'],
            name: $data['name'],
        );
    }

    public static function fromModel(Model $model): static
    {
        return new static(
            id: (int) $model->id,
            email: (string) $model->email,
            name: (string) $model->name,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
        ];
    }
}
