<?php


namespace Framework\Http\Route;

use Framework\Misc\XmlObject;

class XmlRouteBuilder
{
    /**
     * @var XmlObject
     */
    protected $element;

    /**
     * @param XmlObject $element
     */
    public function __construct(XmlObject $element)
    {
        $this->element = $element;
    }

    /**
     * @return RouteInterface
     */
    public function build(): RouteInterface
    {
        return app()->make(RouteInterface::class,
            $this->getRequestMethod(),
            $this->getUriMask(),
            $this->getAs(),
            $this->getController(),
            $this->getUse(),
            $this->getMiddleware(),
            $this->getView());
    }

    /**
     * @return string
     */
    public function getUriMask(): string
    {
        $prefixes = $this->getPrefix();
        array_push($prefixes, $this->element['uri']);
        return implode('/', $prefixes);
    }

    /**
     * @return array
     */
    protected function getPrefix()
    {
        return $this->getAttributeValues('prefix');
    }

    /**
     * returns xml route element's attributes including parent's
     * attributes
     *
     * @param $key
     * @param bool $includingCurrentItem
     * @return array
     */
    protected function getAttributeValues($key, bool $includingCurrentItem = true)
    {
        if ($includingCurrentItem) {
            $elements = $this->element->getParentsWithCurrentNode();
        } else {
            $elements = $this->element->getParents();
        }
        return array_filter(
            array_map(function (XmlObject $element) use ($key) {
                return (string) $element[$key];
            }, $elements)
        );
    }

    /**
     * @return array
     */
    public function getMiddleware()
    {
        return $this->getAttributeValues('middleware');
    }

    /**
     * @return string
     */
    public function getController()
    {
        return (string) $this->getNamespace() . '\\' . $this->getAncestorController();
    }

    /**
     * @return string
     */
    protected function getNamespace()
    {
        return (string) rtrim(implode('\\', $this->getAttributeValues('namespace')), '\\');
    }

    /**
     * @return string
     */
    protected function getAncestorController()
    {
        $controllers = $this->getAttributeValues('controller');

        return (string) array_pop($controllers);
    }

    /**
     * @return string
     */
    public function getUse()
    {
        return (string) $this->element['use'];
    }

    /**
     * @return string
     */
    public function getView()
    {
        return (string) $this->element['view'];
    }

    /**
     * @return string
     */
    public function getAs()
    {
        return (string) implode('.', $this->getAttributeValues('as'));
    }

    /**
     * @return string
     */
    public function getRequestMethod()
    {
        return strtoupper($this->element['method']);
    }
}