<?php

declare(strict_types=1);

namespace Framework\Http;

use App\Http\Exception\RequestParameterException;
use ArrayAccess;
use ArrayIterator;
use Countable;
use Framework\Http\Route\Route;
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

    public Collection $files;

    public readonly ?RequestMethod $requestMethod;

    public ?string $uri;

    public ?Route $route = null;

    private ?array $uriValues = null;

    public readonly Collection $headers;

    public function __construct()
    {
        $this->request = (new Collection(array_merge($_GET, $_POST)))->each(function ($item, $key, $collection) {
            if ($item == "true" || $item == "false") {
                $collection[$key] = filter_var($item, FILTER_VALIDATE_BOOLEAN);
            }
        });

        $this->files = new Collection($_FILES);

        $this->requestMethod = isset($_SERVER['REQUEST_METHOD']) ? RequestMethod::from($_SERVER['REQUEST_METHOD']): null;

        $this->uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH));

        $headers = [];
        foreach ($_SERVER as $headerKey => $headerVal) {
            if (str_starts_with($headerKey, 'HTTP_')) {
                $headers[mb_substr($headerKey, 5)] = $headerVal;
            }
        }
        $this->headers = collect($headers);
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

        $this->uriValues = [];
        $pattern = $this->route->getUriForPregReplace();
        preg_match_all($pattern, $this->uri, $matches);
        foreach ($matches as $key => $value) {
            if (is_string($key) && !empty($value[0])) {
                $this->uriValues[$key] = $value[0];
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

    public function collect($key = null): Collection
    {
        if ($key) {
            return collect($this->request->get($key));
        }

        return $this->request->collect();
    }

    public function stripTags(): static
    {
        foreach ($this->request as $key => $value) {
            $this->request[$key] = strip_tags($value);
        }

        return $this;
    }

    public function validate(...$requestParams): void
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

    public function getHeader(string $name, $default = null)
    {
        return $this->headers->get($name, $default);
    }

    public function token(): ?string
    {
        return $this->get('_token') ?: $this->headers->get('X_CSRF_TOKEN');
    }

    public function referer(): string
    {
        return (string) ($_SERVER['HTTP_REFERER'] ?? '');
    }
}
