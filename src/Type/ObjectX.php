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
     * @param mixed $object
     * @param string|null $errorMessage
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

    /**
     * @inheritDoc
     */
    public function __get(string $key): MixedXInterface
    {
        return Type::mixedX($this->object->$key);
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
     * @inheritDoc
     */
    public function __call(string $name, array $arguments): MixedXInterface
    {
        # @phpstan-ignore-next-line
        return Type::mixedX(call_user_func([$this->object, $name], $arguments));
    }

    /**
     * @inheritDoc
     */
    public function object(): object
    {
        return $this->object;
    }
}