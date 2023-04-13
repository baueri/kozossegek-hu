<?php

declare(strict_types=1);

namespace Framework\Http;

use App\Http\Exception\RequestParameterException;
use ArrayAccess;
use ArrayIterator;
use Countable;
use Framework\Http\Route\RouteInterface;
use Framework\Support\Arr;
use Framework\Support\Collection;
use IteratorAggregate;
use Traversable;

/**
 * @mixin Collection
 */
class Request implements ArrayAccess, Countable, IteratorAggregate
{
    public Collection $request;

    public Collection $headers;

    public Collection $files;

    public ?string $requestMethod;

    public ?string $uri;

    public ?RouteInterface $route = null;

    private ?array $uriValues = null;

    public function __construct()
    {
        $this->request = (new Collection($_REQUEST))->each(function ($item, $key, $collection) {
            if ($item == "true" || $item == "false") {
                $collection[$key] = filter_var($item, FILTER_VALIDATE_BOOLEAN);
            }
        });

        $this->headers = collect(getallheaders());

        $this->files = new Collection($_FILES);

        $this->requestMethod = $_SERVER['REQUEST_METHOD'] ?? null;

        $this->uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH));
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->request, $name], $arguments);
    }

    public function getUriValues(): array
    {
        if (!is_null($this->uriValues)) {
            return $this->uriValues;
        }

        $uriParts = explode('/', trim($this->uri, '/'));
        $uriParts2 = explode('/', trim($this->route->getUriMask(), '/'));
        $this->uriValues = [];
        foreach ($uriParts2 as $i => $uriPart) {
            preg_match_all('/{([a-zA-Z0-9_]+)}/', $uriPart, $matches);
            if ($matches[1]) {
                $this->uriValues[$matches[1][0]] = $uriParts[$i];
            }
        }

        return $this->uriValues;
    }

    public function getUriValue($key)
    {
        return $this->getUriValues()[$key] ?? null;
    }

    public function getIterator(): Traversable|ArrayIterator
    {
        return $this->request->getIterator();
    }

    public function count(): int
    {
        return $this->request->count();
    }

    public function offsetExists($offset): bool
    {
        return $this->request->offsetExists($offset);
    }

    public function offsetGet($offset): mixed
    {
        if ($attribute = $this->getUriValue($offset)) {
            return $attribute;
        }

        return $this->request[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->request->offsetSet($offset, $value);
    }

    public function offsetUnset($offset): void
    {
        $this->request->offsetUnset($offset);
    }

    public function isPostRequestSent(): bool
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    public function collect(): Collection
    {
        return $this->request->collect();
    }

    public function stripTags(): static
    {
        foreach ($this->request as $key => $value) {
            $this->request[$key] = strip_tags($value);
        }

        return $this;
    }

    public function requires(...$requestParams): void
    {
        foreach ($requestParams as $requestParam) {
            if (!$this->request[$requestParam]) {
                throw new RequestParameterException('Missing request value');
            }
        }
    }

    public function isAjax(): bool
    {
        return Arr::get($_SERVER, 'HTTP_X_REQUESTED_WITH') && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'xmlhttprequest';
    }

    public function wantsJson(): bool
    {
        return str_starts_with($this->headers->get('Accept'), 'application/json');
    }

    public function bearerToken(): ?string
    {
        $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

        if (! preg_match('/Bearer\s(\S+)/', (string) $auth, $matches)) {
            return null;
        }

        return $matches[1];
    }

    public function getHeader(string $name, $default = null)
    {
        return $this->headers->get($name, $default);
    }

    public function token(): ?string
    {
        return $this->get('_token') ?: $this->headers->get('X-CSRF-TOKEN');
    }
}
