<?php

declare(strict_types=1);

namespace Takeoto\Type\Scheme;

use Takeoto\Type\Contract\Scheme\MethodArgumentSchemeInterface;

final class MethodArgumentScheme implements MethodArgumentSchemeInterface
{
    private mixed $defaultValue;
    private bool $withDefaultValue = false;

    public function __construct(
        private int|string $name,
        private string $type,
    ) {
    }

    public function getName(): int|string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function hasDefault(): bool
    {
        return $this->withDefaultValue;
    }

    public function getDefault(): mixed
    {
        if (!$this->withDefaultValue) {
            throw new \RuntimeException(sprintf('The default value of "%s" property does not set.', $this->name));
        }

        return $this->defaultValue;
    }

    public function setDefault(mixed $defaultValue): self
    {
        $this->withDefaultValue = true;
        $this->defaultValue = $defaultValue;

        return $this;
    }
}
