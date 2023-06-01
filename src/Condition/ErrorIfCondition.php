<?php

declare(strict_types=1);

namespace Takeoto\Type\Condition;

use Takeoto\Type\Contract\MagicCallableInterface;
use Takeoto\Type\Contract\Scheme\MethodSchemeInterface;
use Takeoto\Type\Contract\TransitionalInterface;
use Takeoto\Type\Scheme\MethodScheme;
use Takeoto\Type\Utility\CallUtility;
use Takeoto\Type\Utility\TypeUtility;

final class ErrorIfCondition implements MagicCallableInterface, TransitionalInterface
{
    public function __construct(private mixed $value, private string $error)
    {
    }

    /**
     * @inheritDoc
     */
    public function supportMagicCall(string $method): bool
    {
        return CallUtility::isTypeExpressionCall($method);
    }

    /**
     * @inheritDoc
     */
    public function __call(string $method, array $arguments): mixed
    {
        if (!$this->supportMagicCall($method)) {
            throw new \RuntimeException(sprintf('Method "%s" does not exist.', $method));
        }

        return $this->verifyValueType(CallUtility::typeExpressionCallToType($method));
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

    /**
     * @param string $type
     * @return mixed
     * @throws \Throwable
     */
    private function verifyValueType(string $type): mixed
    {
        $isVerified = TypeUtility::verifyType($this->value, $type);

        if ($isVerified) {
            TypeUtility::throwWrongTypeException(\sprintf($this->error, $type));
        }

        return $this->value;
    }
}