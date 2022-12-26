<?php

namespace Crastos\Updown\Http\Resources;

use Traversable;

class ResourceRepository implements \Countable, \JsonSerializable, \IteratorAggregate, \ArrayAccess
{
    /** @var array<int, \Crastos\Updown\Http\Resources\Resource> */
    protected array $resources;

    /** @var string|class-string<\Crastos\Updown\Http\Resources\Resource> */
    protected ?string $resource_class;

    /**
     * Create a new resource repository.
     *
     * @param  iterable  $payload
     * @param  class-string<\Crastos\Updown\Http\Resources\Resource>  $resource_class
     */
    public function __construct(iterable $payload, ?string $resource_class = null)
    {
        $this->resource_class = $resource_class;

        try {
            $this->resources = array_values(array_map(fn ($resource) => $this->transform($resource), (array) $payload));
        } catch (\Throwable $e) {
            dd([
                'payload' => $payload,
                'resource_class' => $resource_class,
                'exception' => $e,
            ]);
        }
    }

    public function all(): iterable
    {
        return $this->resources;
    }

    public function first(): ?Resource
    {
        return $this->resources[0] ?? null;
    }

    public function last(): ?Resource
    {
        return $this->resources[count($this->resources) - 1] ?? null;
    }

    public function filter(?callable $callback, $mode = 0): ResourceRepository
    {
        return new static(array_filter($this->resources, $callback, $mode), $this->resource_class);
    }

    public function find(string|int $id): ?Resource
    {
        return $this->filter(fn ($resource) => $resource->{$resource->foreignKey()} === $id)->first();
    }

    public function findOrFail(string|int $id): Resource
    {
        return $this->find($id) ?? throw new \Exception("{$this->resource_class} {$id} not found");
    }

    public function count(): int
    {
        return count($this->resources);
    }

    public function toArray(): array
    {
        return array_map(fn (Resource $resource) => $resource->payload, $this->resources);
    }

    public function toJson($flags = JSON_UNESCAPED_SLASHES): string
    {
        return json_encode($this->toArray(), $flags);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->toArray());
    }

    public function offsetExists(mixed $offset): bool
    {
        assert(is_int($offset), 'Offset must be an integer');

        return isset($this->resources[$offset]);
    }

    public function offsetGet(mixed $offset): Resource
    {
        assert(is_int($offset), 'Offset must be an integer');

        return $this->resources[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $payload): void
    {
        assert(is_int($offset), 'Offset must be an integer');

        $this->resources[$offset] = $this->transform($payload);
    }

    public function offsetUnset(mixed $offset): void
    {
        assert(is_int($offset), 'Offset must be an integer');

        unset($this->resources[$offset]);
    }

    protected function transform($payload): Resource
    {
        if ($payload instanceof Resource) {
            return $payload;
        }

        if (is_null($this->resource_class)) {
            return new class($payload) extends Resource
            {
            };
        }

        return new $this->resource_class($payload);
    }
}
