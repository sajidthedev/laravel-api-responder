# Laravel API Responder

![Packagist Version](https://img.shields.io/packagist/v/sajidthedev/laravel-api-responder)
![Downloads](https://img.shields.io/packagist/dt/sajidthedev/laravel-api-responder)
![Laravel](https://img.shields.io/badge/Laravel-10%2B-red)
![License](https://img.shields.io/github/license/sajidthedev/laravel-api-responder)

A lightweight, opinionated Laravel package that provides **standardized API responses**, **DTO-driven data flow**, **unified error handling**, and **optional API versioning**.

Designed for clean APIs, large teams, and scalable backend systems.

---

## Features

- ✅ **Consistent JSON Contract**: Guaranteed response structure.
- ✅ **DTO Support**: Clean data boundaries and type safety.
- ✅ **Unified Error Handling**: Standardized error codes and details.
- ✅ **Pagination Support**: Automatic meta extraction from Laravel paginators.
- ✅ **Optional Versioning**: Header-based version resolving.
- ✅ **Developer Friendly**: Non-intrusive and PSR-compliant.

## Requirements

- **PHP**: ^8.1
- **Laravel**: ^10.0 | ^11.0 | ^12.0

## Installation

```bash
composer require sajidthedev/laravel-api-responder
```

_No manual service provider registration is required (Laravel auto-discovery)._

### Configuration (Optional)

To publish the configuration file for versioning settings:

```bash
php artisan vendor:publish --provider="ApiResponder\Providers\ApiResponderServiceProvider" --tag="config"
```

---

## Response Contract

### Success Response

```json
{
  "success": true,
  "data": { "id": 1, "name": "John Doe" },
  "meta": { "version": "1.0" },
  "error": null
}
```

### Error Response

```json
{
  "success": false,
  "data": null,
  "meta": {},
  "error": {
    "code": "USER_NOT_FOUND",
    "message": "The user does not exist",
    "details": []
  }
}
```

---

## Usage Guide

### 1. Standard API Responses

Use the `ApiResponse` class for consistent JSON output.

```php
use ApiResponder\Http\Responses\ApiResponse;

// Simple success
return ApiResponse::success(['id' => 1, 'name' => 'John']);

// Success with meta information
return ApiResponse::success($data, ['version' => '1.0']);

// Standardized error
return ApiResponse::error(
    code: 'USER_NOT_FOUND',
    message: 'User does not exist',
    details: [],
    status: 404
);

// Error from registered code
return ApiResponse::errorFromCode('USER_NOT_FOUND', details: ['id' => 1]);
```

### 2. Data Transfer Objects (DTO)

Extend `BaseDTO` for type-safe data handling.

```php
use ApiResponder\DTO\BaseDTO;
use Illuminate\Database\Eloquent\Model;

class UserDTO extends BaseDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $email,
        public readonly string $name
    ) {}

    public static function fromModel(Model $model): static
    {
        return new static($model->id, $model->email, $model->name);
    }

    public static function fromArray(array $data): static
    {
        return new static($data['id'], $data['email'], $data['name']);
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
```

### 3. Pagination Support

Standardize paginated collections effortlessly.

```php
$paginator = User::paginate(15);

return ApiResponse::paginated(
    $paginator,
    fn($user) => UserDTO::fromModel($user)->toArray()
);
```

### 4. Global Error Handling

Integrate the `HandlesApiExceptions` trait into your `app/Exceptions/Handler.php`.

```php
use ApiResponder\Exceptions\HandlesApiExceptions;

class Handler extends ExceptionHandler
{
    use HandlesApiExceptions;

    public function render($request, Throwable $e)
    {
        if ($request->is('api/*')) {
            return $this->handleApiExceptions($e) ?: parent::render($request, $e);
        }

        return parent::render($request, $e);
    }
}
```

## API Versioning (Optional)

Configure supported versions in `config/api_responder.php`.

> **Note:** DTOs are intentionally not versioned. Versioning is applied only at the response layer to avoid duplication and data drift.

Clients specify the version via headers:

- `X-API-Version: v2`
- `Accept-Version: v2`

---

## Error Code Registry (Optional)

Centralize error definitions in `config/api_responder.php`.

```php
'error_codes' => [
    'USER_NOT_FOUND' => [
        'message' => 'The requested user does not exist.',
        'status' => 404
    ],
],
```

Usage in controllers:

```php
return ApiResponse::errorFromCode('USER_NOT_FOUND');
```

## Deprecation Headers (Optional)

Automatically inject RFC-compliant deprecation headers.

### 1. Configuration

Define deprecations by route name or path pattern in `config/api_responder.php`.

```php
'deprecations' => [
    'users.show' => [
        'sunset' => '2026-12-31T00:00:00Z',
        'link' => 'https://api.example.com/docs/v2',
        'message' => 'Endpoint replaced by v2/users/{id}'
    ],
    'api/v1/*' => [
        'sunset' => '2027-01-01T00:00:00Z'
    ],
],
```

### 2. Middleware Registration

Apply the middleware to your API routes:

```php
Route::middleware(\ApiResponder\Deprecation\Middleware\ApiDeprecationHeaders::class)->group(function () {
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
});
```

The middleware injects the following headers when a match is found:

- `Deprecation: true`
- `Sunset`: The sunset timestamp (RFC1123).
- `Link`: Link to documentation with `rel="deprecation"`.
- `X-API-Deprecation-Message`: Custom migration instructions.

---

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
