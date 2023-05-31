<?php

declare(strict_types=1);

namespace Takeoto\Type\Type;

use Takeoto\Type\Contract\TypeX\ObjectXTypeInterface;
use Takeoto\Type\Type;
use Takeoto\Type\Utility\TypeUtility;

class ObjectX implements ObjectXTypeInterface
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
    public function __get(string $key): MixedX
    {
        return MixedX::new($this->object->$key);
    }

    /**
     * @inheritDoc
     */
    public function __call(string $name, array $arguments): MixedX
    {
        # @phpstan-ignore-next-line
        return MixedX::new(call_user_func_array([$this->object, $name], $arguments));
    }

    /**
     * @inheritDoc
     */
    public function instanceOf(string $class): object
    {
        if (!$this->object instanceof $class) {
            TypeUtility::throwWrongTypeException(sprintf(
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
    public function object(): object
    {
        return $this->object;
    }
}