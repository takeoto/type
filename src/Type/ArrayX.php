<?php

declare(strict_types=1);

namespace Takeoto\Type\Type;

use Takeoto\Type\Contract\ArrayXInterface;
use Takeoto\Type\Contract\MixedXInterface;
use Takeoto\Type\Exception\ArrayXKeyNotFound;
use Takeoto\Type\Type;

class ArrayX implements ArrayXInterface
{
    /**
     * @var mixed[]
     */
    private array $array;

    /**
     * @throws \Throwable
     */
    public function __construct(mixed $array, string $errorMessage = null)
    {
        $this->array = Type::array($array, $errorMessage);
    }

    /**
     * @throws \Throwable
     */
    public static function new(mixed $array, string $errorMessage = null): self
    {
        return new self($array, $errorMessage);
    }

    public function get(int|string $key): MixedXInterface
    {
        if (!$this->has($key)) {
            throw new ArrayXKeyNotFound(sprintf('The key "%s" does not exists!', $key));
        }

        return MixedX::new($this->array[$key]);
    }

    public function has(int|string $key): bool
    {
        return array_key_exists($key, $this->array);
    }
}