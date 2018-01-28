<?php

declare(strict_types = 1);

namespace Test;

use Hop\Validator\StdValidator;
use Hop\Validator\Strategy\Field;
use Hop\Validator\Strategy\Strategy;
use Hop\Validator\Validator;
use PHPUnit\Framework\TestCase;

class StdValidatorTest extends TestCase
{
    /**
     * @var StdValidator
     */
    private $validator;

    public function setUp()
    {
        $this->validator = new StdValidator();
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(Validator::class, $this->validator);
    }

    public function test_notExisitingRule()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->validator->isValid(['field' => 'value'], new class implements Strategy {
            public function getFields(): array
            {
                $field = new Field(
                    'field',
                    true,
                    null
                );

                $field->registerValidator('notExistingValidator', null);

                return [
                    $field
                ];
            }
        });
    }

    public function test_passesValidation()
    {
        $strategyMock = $this->createMock(Strategy::class);
        $field = new Field('param', true, null);
        $field->registerValidator('passes', null);

        $strategyMock->method('getFields')
            ->willReturn([
                $field
            ]);

        $ruleValidatorMock = $this->createMock(Validator\RuleValidator::class);
        $ruleValidatorMock->method('isValid')
            ->willReturn(true);

        $ruleValidatorMock->method('getMessage')
            ->willReturn(null);

        $input = [
            'param' => 'passes'
        ];

        $this->validator->registerRuleValidator('passes', $ruleValidatorMock);

        $this->assertTrue($this->validator->isValid($input, $strategyMock));
        $this->assertCount(0, $this->validator->getMessages($input, $strategyMock));
    }

    public function test_notPassesValidation()
    {
        $strategyMock = $this->createMock(Strategy::class);
        $field = new Field('param', true, null);
        $field->registerValidator('passes', null);
        $strategyMock->method('getFields')
            ->willReturn([
                $field
            ]);

        $ruleValidatorMock = $this->createMock(Validator\RuleValidator::class);
        $ruleValidatorMock->method('isValid')
            ->willReturn(false);

        $ruleValidatorMock->method('getMessage')
            ->willReturn('Error');

        $input = [
            'param' => 'passes'
        ];

        $this->validator->registerRuleValidator('passes', $ruleValidatorMock);

        $this->assertFalse($this->validator->isValid($input, $strategyMock));
        $this->assertCount(1, $this->validator->getMessages($input, $strategyMock));
    }

    public function test_notRequiredField()
    {
        $strategyMock = $this->createMock(Strategy::class);
        $field = new Field('param', false, null);
        $field->registerValidator('passes', null);
        $strategyMock->method('getFields')
            ->willReturn([
                $field
            ]);

        $ruleValidatorMock = $this->createMock(Validator\RuleValidator::class);
        $ruleValidatorMock->method('isValid')
            ->willReturn(false);

        $ruleValidatorMock->method('getMessage')
            ->willReturn('Error');

        $input = [
        ];

        $this->validator->registerRuleValidator('passes', $ruleValidatorMock);

        $this->assertTrue($this->validator->isValid($input, $strategyMock));
        $this->assertCount(0, $this->validator->getMessages($input, $strategyMock));
    }

    public function test_requiredField()
    {
        $strategyMock = $this->createMock(Strategy::class);
        $field = new Field('param', true, null);
        $field->registerValidator('passes', null);
        $strategyMock->method('getFields')
            ->willReturn([
                $field
            ]);

        $ruleValidatorMock = $this->createMock(Validator\RuleValidator::class);
        $ruleValidatorMock->method('isValid')
            ->willReturn(false);

        $ruleValidatorMock->method('getMessage')
            ->willReturn('Error');

        $input = [
        ];

        $this->validator->registerRuleValidator('passes', $ruleValidatorMock);

        $this->assertFalse($this->validator->isValid($input, $strategyMock));
        $this->assertCount(1, $this->validator->getMessages($input, $strategyMock));
    }

    public function test_excludeCondition()
    {
        $strategyMock = $this->createMock(Strategy::class);
        $field = new Field('param', true, function (array $data) {
            return !isset($data['exclude']);
        });
        $field->registerValidator('passes', null);
        $strategyMock->method('getFields')
            ->willReturn([
                $field
            ]);

        $ruleValidatorMock = $this->createMock(Validator\RuleValidator::class);
        $ruleValidatorMock->method('isValid')
            ->willReturn(false);

        $ruleValidatorMock->method('getMessage')
            ->willReturn('Error');

        $input = [
            'exclude' => true
        ];

        $this->validator->registerRuleValidator('passes', $ruleValidatorMock);

        $this->assertTrue($this->validator->isValid($input, $strategyMock));
        $this->assertCount(0, $this->validator->getMessages($input, $strategyMock));
    }

    public function test_excludeConditionOff()
    {
        $strategyMock = $this->createMock(Strategy::class);
        $field = new Field('param', true, function (array $data) {
            return !isset($data['exclude']);
        });
        $field->registerValidator('passes', null);
        $strategyMock->method('getFields')
            ->willReturn([
                $field
            ]);

        $ruleValidatorMock = $this->createMock(Validator\RuleValidator::class);
        $ruleValidatorMock->method('isValid')
            ->willReturn(false);

        $ruleValidatorMock->method('getMessage')
            ->willReturn('Error');

        $input = [

        ];

        $this->validator->registerRuleValidator('passes', $ruleValidatorMock);

        $this->assertFalse($this->validator->isValid($input, $strategyMock));
        $this->assertCount(1, $this->validator->getMessages($input, $strategyMock));
    }
}
