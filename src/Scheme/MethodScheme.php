<?php

declare(strict_types=1);

namespace Takeoto\Type\Scheme;

use Takeoto\Type\Contract\Scheme\MethodSchemeInterface;

final class MethodScheme implements MethodSchemeInterface
{
    private string $returnType;

    /**
     * @var array<int|string,MethodArgumentScheme>
     */
    private array $arguments = [];

    public function __construct(private string $name)
    {
    }

    public static function new(string $name): self
    {
        return new self($name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function getReturnType(): string
    {
        return $this->returnType;
    }

    /**
     * @param int|string $name
     * @param string $type
     * @return self
     */
    public function arg(int|string $name, string $type): self
    {
        $this->arguments[$name] = new MethodArgumentScheme($name, $type);

        return $this;
    }

    public function default(mixed $value, int|string $key = null): self
    {
        $key ??= array_key_last($this->arguments) ?? throw new \RuntimeException(
            'The default value argument key should be provided.'
        );

        if (!array_key_exists($key, $this->arguments)) {
            return $this;
        }

        $this->arguments[$key]->setDefault($value);

        return $this;
    }

    public function return(string $type): self
    {
        $this->returnType = $type;

        return $this;
    }
}