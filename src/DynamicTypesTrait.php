<?php

namespace Takeoto\Type;

use Takeoto\Type\Contract\Scheme\MethodSchemeInterface;
use Takeoto\Type\Utility\CallUtility;

trait DynamicTypesTrait
{
    /**
     * @inheritDoc
     */
    public static function supportMagicStaticCall(string $method): bool
    {
        return CallUtility::isTypeExpressionCall($method) || CallUtility::isTransitCall($method, static::class);
    }

    /**
     * @inheritDoc
     */
    public static function __callStatic(string $method, array $arguments): mixed
    {
        if (!self::supportMagicStaticCall($method)) {
            throw new \RuntimeException(sprintf('Method "%s" does not exist.', $method));
        }

        if (CallUtility::isTypeExpressionCall($method)) {
            return CallUtility::strictTypeCall($method, $arguments);
        }

        return CallUtility::callTransit($method, $arguments, self::class);
    }

    /**
     * @inheritDoc
     */
    public static function getMethodScheme(string $method): ?MethodSchemeInterface
    {
        return CallUtility::getSelfMethodSchema($method, static::class);
    }
}