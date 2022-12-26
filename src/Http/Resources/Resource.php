<?php

namespace Crastos\Updown\Http\Resources;

use Crastos\Updown\Concerns\GeneratesClassNames;
use Crastos\Updown\Http\Endpoints\Endpoint;
use DateTime;
use ReflectionClass;
use ReflectionProperty;
use ReflectionUnionType;

abstract class Resource
{
    use GeneratesClassNames;

    public readonly iterable $payload;

    public function __construct(iterable|string|int $payload = [], protected ?Endpoint $endpoint = null)
    {
        if (is_string($payload) || is_int($payload)) {
            $payload = [$this->foreignKey() => $payload];
        }

        $this->payload = array_filter((array) $payload, fn ($key) => property_exists($this, $key), ARRAY_FILTER_USE_KEY);
    }

    public static function foreignKey()
    {
        return 'token';
    }

    public function transform($key, $value)
    {
        $type = (new ReflectionProperty($this, $key))->getType();

        if ($type instanceof ReflectionUnionType) {
            $type = $type->getTypes()[0];
        }

        $type = $type->getName();

        if (method_exists($this, $method = "transform{$this->generateClassName($type)}")) {
            return $this->{$method}($value, $key, $type);
        }

        if (method_exists($this, $method = "transform{$this->generateClassName($key)}")) {
            return $this->{$method}($value, $key, $type);
        }

        if (class_exists($type)) {
            if (method_exists($this, $method = "transform{$this->generateClassName((new ReflectionClass($type))->getShortName())}")) {
                return $this->{$method}($value, $key, $type);
            }

            if (is_subclass_of($type, ResourceRepository::class)) {
                $this->transformResourceRepository($type, $key, $value);
            }

            return new $type($value);
        }

        return match ($type) {
            'int' => (int) $value,
            'float' => (float) $value,
            'bool' => (bool) $value,
            'string' => (string) $value,
            'array' => (array) $value,
            'object' => (object) $value,
            'null' => null,
            default => $value,
        };
    }

    protected function transformResourceRepository($data, $key, $repositoryClassName)
    {
        $namespace = __NAMESPACE__;

        // e.g., <Resource>::checks -> Checks
        $classBaseName = $this->generateClassName($key);
        $className = "{$namespace}\\{$classBaseName}";
        if (class_exists($className)) {
            return new $repositoryClassName($data, $className);
        }

        // e.g., <Resource>::checks -> Check
        $className = substr($className, 0, -1);
        if (class_exists($className)) {
            return new $repositoryClassName($data, $className);
        }

        // e.g., <Check>::Ssl -> Check\Ssl
        $namespace .= (new ReflectionClass($this))->getShortName();
        $className = "{$namespace}\\{$classBaseName}";
        if (class_exists($className)) {
            return new $repositoryClassName($data, $className);
        }

        throw new \Exception("Could not resolve resource class for {$key}");
    }

    protected function transformDateTimeInterface($value, $key, $type)
    {
        return is_null($value) ? $value : new DateTime($value);
    }

    protected function transformRecipient($value, $key, $type)
    {
        return new Recipient(is_string($value) ? ['id' => $value] : $value);
    }

    public function __get($name)
    {
        return $this->endpoint->{$name};
    }

    public function __call($name, $arguments)
    {
        if (in_array($name, ['get', 'update', 'edit', 'delete'])) {
            return $this->endpoint->{$name}($this, ...$arguments);
        }

        return $this->endpoint->{$name}(...$arguments);
    }
}
