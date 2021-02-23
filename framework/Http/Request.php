<?php

namespace Framework\Http;

use App\Http\Exception\RequestParameterException;
use ArrayAccess;
use Countable;
use Framework\Support\Collection;
use InvalidArgumentException;
use IteratorAggregate;

/**
 * Class Request
 * @package Framework\Http
 * @mixin Collection
 */
class Request implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * @var Collection
     */
    public $request;

    /**
     * @var Collection
     */
    public $files;

    /**
     * @var string
     */
    public $requestMethod;

    /**
     * @var string
     */
    public $uri;

    /**
     *
     * @var Route\Route
     */
    public $route;

    /**
     * @var array
     */
    protected $uriValues;

    public function __construct()
    {

        $this->request = (new Collection($_REQUEST))->each(function ($item, $key, $collection) {
            if ($item == "true" || $item == "false") {
                $collection[$key] = filter_var($item, FILTER_VALIDATE_BOOLEAN);
            }
        });

        $this->files = new Collection($_FILES);

        $this->requestMethod = $_SERVER['REQUEST_METHOD'];

        $this->uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->request, $name], $arguments);
    }

    /**
     * @return array
     */
    public function getUriValues()
    {
        if (is_null($this->uriValues)) {
            $uriParts = explode('/', trim($this->uri, '/'));
            $uriParts2 = explode('/', trim($this->route->getUriMask(), '/'));
            $this->uriValues = [];
            foreach ($uriParts2 as $i => $uriPart) {
                preg_match_all('/{([a-zA-Z0-9\_]+)}/', $uriPart, $matches);
                if ($matches[1]) {
                    $this->uriValues[$matches[1][0]] = $uriParts[$i];
                }
            }
        }

        return $this->uriValues;
    }

    /**
     * @param string $key
     * @return string|int|null
     */
    public function getUriValue($key)
    {
        return $this->getUriValues()[$key] ?? null;
    }

    public function getIterator()
    {
        return $this->request->getIterator();
    }

    public function count()
    {
        return $this->request->count();
    }

    public function offsetExists($offset)
    {
        return $this->request->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        if ($attribute = $this->getUriValue($offset)) {
            return $attribute;
        }

        return $this->request[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->request->offsetSet($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->request->offsetUnset($offset);
    }

    public function postRequestSent()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    public function collect()
    {
        return $this->request->collect();
    }

    public function stripTags()
    {
        foreach ($this->request as $key => $value) {
            $this->request[$key] = strip_tags($value);
        }

        return $this;
    }

    public function validate(...$requestParams)
    {
        foreach ($requestParams as $requestParam) {
            if (!$this->request[$requestParam]) {
                throw new RequestParameterException('Missing request value');
            }
        }
    }
}
