<?php

declare(strict_types=1);

namespace Takeoto\tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Takeoto\Type\Type;

#[CoversClass(Type::class)]
class TypeTest extends TestCase
{
    public static function baseTypesProvider(): iterable
    {
        return [
            ['int', 0, null, null],
            ['int', 1, null, null],
            ['int', '1', null, 'Expected int type, string given'],
            ['int', '1Abc', null, 'Expected int type, string given'],
            ['int', '', null, 'Expected int type, string given'],
            ['int', 0.0, null, 'Expected int type, double given'],
            ['int', 1.1, null, 'Expected int type, double given'],
            ['int', new \stdClass(), null, 'Expected int type, stdClass given'],
            ['int', [], null, 'Expected int type, array given'],
            ['int', [1], null, 'Expected int type, array given'],
            ['int', true, null, 'Expected int type, boolean given'],
            ['int', false, null, 'Expected int type, boolean given'],
            ['int', static fn() => null, null, 'Expected int type, Closure given'],
            ['int', [Type::class, 'int'], null, 'Expected int type, array given'],
            ['int', null, null, 'Expected int type, NULL given'],
            ['int', (fn(): iterable => yield null)(), null, 'Expected int type, Generator given'],
            ['int', null, 'Custom error [%s type, given %s]', 'Custom error [int type, given NULL]'],

            ['float', 0, null, 'Expected float type, integer given'],
            ['float', 1, null, 'Expected float type, integer given'],
            ['float', '1', null, 'Expected float type, string given'],
            ['float', '1Abc', null, 'Expected float type, string given'],
            ['float', '', null, 'Expected float type, string given'],
            ['float', 0.0, null, null],
            ['float', 1.1, null, null],
            ['float', new \stdClass(), null, 'Expected float type, stdClass given'],
            ['float', [], null, 'Expected float type, array given'],
            ['float', [1], null, 'Expected float type, array given'],
            ['float', true, null, 'Expected float type, boolean given'],
            ['float', false, null, 'Expected float type, boolean given'],
            ['float', static fn() => null, null, 'Expected float type, Closure given'],
            ['float', [Type::class, 'float'], null, 'Expected float type, array given'],
            ['float', null, null, 'Expected float type, NULL given'],
            ['float', (fn(): iterable => yield null)(), null, 'Expected float type, Generator given'],
            ['float', null, 'Custom error [%s type, given %s]', 'Custom error [float type, given NULL]'],

            ['string', 0, null, 'Expected string type, integer given'],
            ['string', 1, null, 'Expected string type, integer given'],
            ['string', '1', null, null],
            ['string', '1Abc', null, null],
            ['string', '', null, null],
            ['string', 0.0, null, 'Expected string type, double given'],
            ['string', 1.1, null, 'Expected string type, double given'],
            ['string', new \stdClass(), null, 'Expected string type, stdClass given'],
            ['string', [], null, 'Expected string type, array given'],
            ['string', [1], null, 'Expected string type, array given'],
            ['string', true, null, 'Expected string type, boolean given'],
            ['string', false, null, 'Expected string type, boolean given'],
            ['string', static fn() => null, null, 'Expected string type, Closure given'],
            ['string', [Type::class, 'string'], null, 'Expected string type, array given'],
            ['string', null, null, 'Expected string type, NULL given'],
            ['string', (fn(): iterable => yield null)(), null, 'Expected string type, Generator given'],
            ['string', null, 'Custom error [%s type, given %s]', 'Custom error [string type, given NULL]'],

            ['object', 0, null, 'Expected object type, integer given'],
            ['object', 1, null, 'Expected object type, integer given'],
            ['object', '1', null, 'Expected object type, string given'],
            ['object', '1Abc', null, 'Expected object type, string given'],
            ['object', '', null, 'Expected object type, string given'],
            ['object', 0.0, null, 'Expected object type, double given'],
            ['object', 1.1, null, 'Expected object type, double given'],
            ['object', new \stdClass(), null, null],
            ['object', [], null, 'Expected object type, array given'],
            ['object', [1], null, 'Expected object type, array given'],
            ['object', true, null, 'Expected object type, boolean given'],
            ['object', false, null, 'Expected object type, boolean given'],
            ['object', static fn() => null, null, null],
            ['object', [Type::class, 'object'], null, 'Expected object type, array given'],
            ['object', null, null, 'Expected object type, NULL given'],
            ['object', (fn(): iterable => yield null)(), null, null],
            ['object', null, 'Custom error [%s type, given %s]', 'Custom error [object type, given NULL]'],

            ['array', 0, null, 'Expected array type, integer given'],
            ['array', 1, null, 'Expected array type, integer given'],
            ['array', '1', null, 'Expected array type, string given'],
            ['array', '1Abc', null, 'Expected array type, string given'],
            ['array', '', null, 'Expected array type, string given'],
            ['array', 0.0, null, 'Expected array type, double given'],
            ['array', 1.1, null, 'Expected array type, double given'],
            ['array', new \stdClass(), null, 'Expected array type, stdClass given'],
            ['array', [], null, null],
            ['array', [1], null, null],
            ['array', true, null, 'Expected array type, boolean given'],
            ['array', false, null, 'Expected array type, boolean given'],
            ['array', static fn() => null, null, 'Expected array type, Closure given'],
            ['array', [Type::class, 'array'], null, null],
            ['array', null, null, 'Expected array type, NULL given'],
            ['array', (fn(): iterable => yield null)(), null, 'Expected array type, Generator given'],
            ['array', null, 'Custom error [%s type, given %s]', 'Custom error [array type, given NULL]'],

            ['bool', 0, null, 'Expected bool type, integer given'],
            ['bool', 1, null, 'Expected bool type, integer given'],
            ['bool', '1', null, 'Expected bool type, string given'],
            ['bool', '1Abc', null, 'Expected bool type, string given'],
            ['bool', '', null, 'Expected bool type, string given'],
            ['bool', 0.0, null, 'Expected bool type, double given'],
            ['bool', 1.1, null, 'Expected bool type, double given'],
            ['bool', new \stdClass(), null, 'Expected bool type, stdClass given'],
            ['bool', [], null, 'Expected bool type, array given'],
            ['bool', [1], null, 'Expected bool type, array given'],
            ['bool', true, null, null],
            ['bool', false, null, null],
            ['bool', static fn() => null, null, 'Expected bool type, Closure given'],
            ['bool', [Type::class, 'bool'], null, 'Expected bool type, array given'],
            ['bool', null, null, 'Expected bool type, NULL given'],
            ['bool', (fn(): iterable => yield null)(), null, 'Expected bool type, Generator given'],
            ['bool', null, 'Custom error [%s type, given %s]', 'Custom error [bool type, given NULL]'],

            ['callable', 0, null, 'Expected callable type, integer given'],
            ['callable', 1, null, 'Expected callable type, integer given'],
            ['callable', '1', null, 'Expected callable type, string given'],
            ['callable', '1Abc', null, 'Expected callable type, string given'],
            ['callable', '', null, 'Expected callable type, string given'],
            ['callable', 0.0, null, 'Expected callable type, double given'],
            ['callable', 1.1, null, 'Expected callable type, double given'],
            ['callable', new \stdClass(), null, 'Expected callable type, stdClass given'],
            ['callable', [], null, 'Expected callable type, array given'],
            ['callable', [1], null, 'Expected callable type, array given'],
            ['callable', true, null, 'Expected callable type, boolean given'],
            ['callable', false, null, 'Expected callable type, boolean given'],
            ['callable', static fn() => null, null, null],
            ['callable', [Type::class, 'callable'], null, null],
            ['callable', null, null, 'Expected callable type, NULL given'],
            ['callable', (fn(): iterable => yield null)(), null, 'Expected callable type, Generator given'],
            ['callable', null, 'Custom error [%s type, given %s]', 'Custom error [callable type, given NULL]'],

            ['null', 0, null, 'Expected null type, integer given'],
            ['null', 1, null, 'Expected null type, integer given'],
            ['null', '1', null, 'Expected null type, string given'],
            ['null', '1Abc', null, 'Expected null type, string given'],
            ['null', '', null, 'Expected null type, string given'],
            ['null', 0.0, null, 'Expected null type, double given'],
            ['null', 1.1, null, 'Expected null type, double given'],
            ['null', new \stdClass(), null, 'Expected null type, stdClass given'],
            ['null', [], null, 'Expected null type, array given'],
            ['null', [1], null, 'Expected null type, array given'],
            ['null', true, null, 'Expected null type, boolean given'],
            ['null', false, null, 'Expected null type, boolean given'],
            ['null', static fn() => null, null, 'Expected null type, Closure given'],
            ['null', [Type::class, 'null'], null, 'Expected null type, array given'],
            ['null', null, null, null],
            ['null', (fn(): iterable => yield null)(), null, 'Expected null type, Generator given'],
            ['null', 'NOT NULL', 'Custom error [%s type, given %s]', 'Custom error [null type, given string]'],
        ];
    }

    #[DataProvider('baseTypesProvider')]
    public function testBaseTypes(string $method, mixed $value, ?string $error, ?string $exception): void
    {
        if ($exception !== null) {
            self::expectException(\InvalidArgumentException::class);
            self::expectExceptionMessage($exception);
        }

        self::assertSame($value, call_user_func([Type::class, $method], $value, $error));
    }

    public static function basePseudoTypesProvider(): iterable
    {
        return [
            ['iterable', 0, null, 'Expected iterable type, integer given'],
            ['iterable', 1, null, 'Expected iterable type, integer given'],
            ['iterable', '1', null, 'Expected iterable type, string given'],
            ['iterable', '1Abc', null, 'Expected iterable type, string given'],
            ['iterable', '', null, 'Expected iterable type, string given'],
            ['iterable', 0.0, null, 'Expected iterable type, double given'],
            ['iterable', 1.1, null, 'Expected iterable type, double given'],
            ['iterable', new \stdClass(), null, 'Expected iterable type, stdClass given'],
            ['iterable', [], null, null],
            ['iterable', [1], null, null],
            ['iterable', true, null, 'Expected iterable type, boolean given'],
            ['iterable', false, null, 'Expected iterable type, boolean given'],
            ['iterable', static fn() => null, null, 'Expected iterable type, Closure given'],
            ['iterable', [Type::class, 'int'], null, null],
            ['iterable', null, null, 'Expected iterable type, NULL given'],
            ['iterable', (fn(): iterable => yield null)(), null, null],
            ['iterable', null, 'Custom error [%s type, given %s]', 'Custom error [iterable type, given NULL]'],

            ['numeric', 0, null, null],
            ['numeric', 1, null, null],
            ['numeric', '1', null, null],
            ['numeric', '1Abc', null, 'Expected numeric type, string given'],
            ['numeric', '', null, 'Expected numeric type, string given'],
            ['numeric', 0.0, null, null],
            ['numeric', 1.1, null, null],
            ['numeric', new \stdClass(), null, 'Expected numeric type, stdClass given'],
            ['numeric', [], null, 'Expected numeric type, array given'],
            ['numeric', [1], null, 'Expected numeric type, array given'],
            ['numeric', true, null, 'Expected numeric type, boolean given'],
            ['numeric', false, null, 'Expected numeric type, boolean given'],
            ['numeric', static fn() => null, null, 'Expected numeric type, Closure given'],
            ['numeric', [Type::class, 'int'], null, 'Expected numeric type, array given'],
            ['numeric', null, null, 'Expected numeric type, NULL given'],
            ['numeric', (fn(): iterable => yield null)(), null, 'Expected numeric type, Generator given'],
            ['numeric', null, 'Custom error [%s type, given %s]', 'Custom error [numeric type, given NULL]'],

            ['true', 0, null, 'Expected true type, integer given'],
            ['true', 1, null, 'Expected true type, integer given'],
            ['true', '1', null, 'Expected true type, string given'],
            ['true', '1Abc', null, 'Expected true type, string given'],
            ['true', '', null, 'Expected true type, string given'],
            ['true', 0.0, null, 'Expected true type, double given'],
            ['true', 1.1, null, 'Expected true type, double given'],
            ['true', new \stdClass(), null, 'Expected true type, stdClass given'],
            ['true', [], null, 'Expected true type, array given'],
            ['true', [1], null, 'Expected true type, array given'],
            ['true', true, null, null],
            ['true', false, null, 'Expected true type, boolean given'],
            ['true', static fn() => null, null, 'Expected true type, Closure given'],
            ['true', [Type::class, 'int'], null, 'Expected true type, array given'],
            ['true', null, null, 'Expected true type, NULL given'],
            ['true', (fn(): iterable => yield null)(), null, 'Expected true type, Generator given'],
            ['true', null, 'Custom error [%s type, given %s]', 'Custom error [true type, given NULL]'],

            ['false', 0, null, 'Expected false type, integer given'],
            ['false', 1, null, 'Expected false type, integer given'],
            ['false', '1', null, 'Expected false type, string given'],
            ['false', '1Abc', null, 'Expected false type, string given'],
            ['false', '', null, 'Expected false type, string given'],
            ['false', 0.0, null, 'Expected false type, double given'],
            ['false', 1.1, null, 'Expected false type, double given'],
            ['false', new \stdClass(), null, 'Expected false type, stdClass given'],
            ['false', [], null, 'Expected false type, array given'],
            ['false', [1], null, 'Expected false type, array given'],
            ['false', true, null, 'Expected false type, boolean given'],
            ['false', false, null, null],
            ['false', static fn() => null, null, 'Expected false type, Closure given'],
            ['false', [Type::class, 'int'], null, 'Expected false type, array given'],
            ['false', null, null, 'Expected false type, NULL given'],
            ['false', (fn(): iterable => yield null)(), null, 'Expected false type, Generator given'],
            ['false', null, 'Custom error [%s type, given %s]', 'Custom error [false type, given NULL]'],

        ];
    }

    #[DataProvider('basePseudoTypesProvider')]
    public function testPseudoTypes(string $method, mixed $value, ?string $error, ?string $exception): void
    {
        if ($exception !== null) {
            self::expectException(\InvalidArgumentException::class);
            self::expectExceptionMessage($exception);
        }

        self::assertSame($value, call_user_func([Type::class, $method], $value, $error));
    }

    public static function baseCustomTypesProvider(): iterable
    {
        return [
            ['stringInt', 0, null, 'Expected stringInt type, integer given'],
            ['stringInt', 1, null, 'Expected stringInt type, integer given'],
            ['stringInt', '1', null, null],
            ['stringInt', '1Abc', null, 'Expected stringInt type, string given'],
            ['stringInt', '', null, 'Expected stringInt type, string given'],
            ['stringInt', 0.0, null, 'Expected stringInt type, double given'],
            ['stringInt', 1.1, null, 'Expected stringInt type, double given'],
            ['stringInt', new \stdClass(), null, 'Expected stringInt type, stdClass given'],
            ['stringInt', [], null, 'Expected stringInt type, array given'],
            ['stringInt', [1], null, 'Expected stringInt type, array given'],
            ['stringInt', true, null, 'Expected stringInt type, boolean given'],
            ['stringInt', false, null, 'Expected stringInt type, boolean given'],
            ['stringInt', static fn() => null, null, 'Expected stringInt type, Closure given'],
            ['stringInt', [Type::class, 'int'], null, 'Expected stringInt type, array given'],
            ['stringInt', null, null, 'Expected stringInt type, NULL given'],
            ['stringInt', (fn(): iterable => yield null)(), null, 'Expected stringInt type, Generator given'],
            ['stringInt', null, 'Custom error [%s type, given %s]', 'Custom error [stringInt type, given NULL]'],

            ['empty', 0, null, null],
            ['empty', 1, null, 'Expected empty type, integer given'],
            ['empty', '1', null, 'Expected empty type, string given'],
            ['empty', '1Abc', null, 'Expected empty type, string given'],
            ['empty', '', null, null],
            ['empty', 0.0, null, null],
            ['empty', 1.1, null, 'Expected empty type, double given'],
            ['empty', new \stdClass(), null, 'Expected empty type, stdClass given'],
            ['empty', [], null, null],
            ['empty', [1], null, 'Expected empty type, array given'],
            ['empty', true, null, 'Expected empty type, boolean given'],
            ['empty', false, null, null],
            ['empty', static fn() => null, null, 'Expected empty type, Closure given'],
            ['empty', [Type::class, 'int'], null, 'Expected empty type, array given'],
            ['empty', null, null, null],
            ['empty', (fn(): iterable => yield null)(), null, 'Expected empty type, Generator given'],
            ['empty', 'NOT EMPTY', 'Custom error [%s type, given %s]', 'Custom error [empty type, given string]'],

        ];
    }

    #[DataProvider('baseCustomTypesProvider')]
    public function testCustomTypes(string $method, mixed $value, ?string $error, ?string $exception): void
    {
        if ($exception !== null) {
            self::expectException(\InvalidArgumentException::class);
            self::expectExceptionMessage($exception);
        }

        self::assertSame($value, call_user_func([Type::class, $method], $value, $error));
    }
}