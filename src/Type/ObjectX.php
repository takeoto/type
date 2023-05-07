<?php

declare(strict_types=1);

namespace Takeoto\Type\Type;

use Takeoto\Type\Contract\MixedXInterface;
use Takeoto\Type\Contract\ObjectXInterface;
use Takeoto\Type\Type;

class ObjectX implements ObjectXInterface
{
    private object $object;

    /**
     * @throws \Throwable
     */
    public function __construct(mixed $object, string $errorMessage = null)
    {
        $this->object = Type::object($object, $errorMessage);
    }

    /**
     * @param mixed $object
     * @param string|null $errorMessage
     * @return self
     * @throws \Throwable
     */
    public static function new(mixed $object, string $errorMessage = null): self
    {
        return new self($object, $errorMessage);
    }

    public function __get(string $key): MixedXInterface
    {
        return MixedX::new($this->object->$key);
    }

    /**
     * @inheritDoc
     */
    public function instanceOf(string $class): object
    {
        if (!$this->object instanceof $class) {
            throw new \RuntimeException(sprintf(
                'Expected an instance of %2$s. Got: %s',
                \get_class($this->object),
                $class,
            ));
        }

        return $this->object;
    }

    /**
     * @param string $name
     * @param array<int,mixed> $arguments
     * @return MixedXInterface
     */
    public function __call(string $name, array $arguments): MixedXInterface
    {
        # @phpstan-ignore-next-line
        return MixedX::new(call_user_func([$this->object, $name], $arguments));
    }
}