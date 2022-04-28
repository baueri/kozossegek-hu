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

    public function getUriMask(): string
    {
        $prefixes = $this->getPrefix();
        $prefixes[] = $this->element['uri'];
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

    public function getController($withNamespace = true): string
    {
        if (!$withNamespace) {
            return $this->getAncestorController();
        }

        return $this->getNamespace() . '\\' . $this->getAncestorController();
    }

    protected function getNamespace(): string
    {
        return rtrim(implode('\\', $this->getAttributeValues('namespace')), '\\');
    }

    protected function getAncestorController(): string
    {
        $controllers = $this->getAttributeValues('controller');

        return (string) array_pop($controllers);
    }

    public function getUse(): string
    {
        return (string) $this->element['use'];
    }

    public function getView(): string
    {
        return (string) $this->element['view'];
    }

    public function getAs(): string
    {
        return implode('.', $this->getAttributeValues('as'));
    }

    public function getRequestMethod(): string
    {
        return strtoupper($this->element['method']);
    }
}
