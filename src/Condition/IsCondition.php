<?php

declare(strict_types=1);

namespace Takeoto\Type\Condition;

use Takeoto\Type\Contract\MagicCallableInterface;
use Takeoto\Type\Contract\Scheme\MethodSchemeInterface;
use Takeoto\Type\Contract\TransitionalInterface;
use Takeoto\Type\Scheme\MethodScheme;
use Takeoto\Type\Utility\CallUtility;
use Takeoto\Type\Utility\TypeUtility;

final class IsCondition implements MagicCallableInterface, TransitionalInterface
{
    public function __construct(private mixed $value)
    {
    }

    /**
     * @inheritDoc
     */
    public function supportMagicCall(string $method): bool
    {
        return CallUtility::isTypeExpressionCall($method);
    }

    public function __call(string $method, array $arguments): bool
    {
        if (!$this->supportMagicCall($method)) {
            throw new \RuntimeException(sprintf('Method "%s" does not exist.', $method));
        }

        return TypeUtility::verifyType($this->value, CallUtility::typeExpressionCallToType($method));
    }

    /**
     * @inheritDoc
     */
    public static function getMethodScheme(string $method): ?MethodSchemeInterface
    {
        return CallUtility::isTypeExpressionCall($method)
            ? MethodScheme::new($method)->return(CallUtility::typeExpressionCallToType($method))
            : null;
    }
}