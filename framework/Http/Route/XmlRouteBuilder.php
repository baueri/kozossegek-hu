<?php


namespace Framework\Http\Route;

use Framework\Misc\XmlObject;

class XmlRouteBuilder
{
    protected XmlObject $element;

    /**
     * @param XmlObject $element
     */
    public function __construct(XmlObject $element)
    {
        $this->element = $element;
    }

    public function build(): RouteInterface
    {
        return app()->make(RouteInterface::class,
            [
                $this->getRequestMethod(),
                $this->getUriMask(),
                $this->getAs(),
                $this->getController(),
                $this->getUse(),
                $this->getMiddleware(),
                $this->getView()
            ]
        );
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

    protected function getPrefix(): array
    {
        return $this->getAttributeValues('prefix');
    }

    /**
     * returns xml route element's attributes including parent's attributes
     */
    protected function getAttributeValues(string $key): array
    {
        $elements = $this->element->getParentsWithCurrentNode();
        return array_filter(
            array_map(function (XmlObject $element) use ($key) {
                return (string) $element[$key];
            }, $elements)
        );
    }

    protected function getParentProperties($propertyName): array
    {
        $elements = $this->element->getParents();
        $parentProperties = [];
        foreach ($elements as $element) {
            if ($element->{$propertyName}) {
                foreach ($element->{$propertyName} as $property) {
                    $parentProperties[] = $property;
                }
            }
        }
        return $parentProperties;
    }

    public function getMiddleware(): array
    {
        $middleware = $this->getAttributeValues('middleware');
        $parentProperties = $this->getParentProperties('middleware');
        return collect($parentProperties)
            ->map(function(XmlObject $middleware){
                return (string) $middleware['name'];
            })
            ->merge($middleware)
            ->unique()
            ->all();
    }

    /**
     * @return string
     */
    public function getController($withNamespace = true)
    {
        if (!$withNamespace) {
            
            return (string) $this->getAncestorController();
        }

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
