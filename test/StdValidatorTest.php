<?php

declare(strict_types = 1);

namespace Test;

use Hop\Validator\StdValidator;
use Hop\Validator\Strategy\Field;
use Hop\Validator\Strategy\Strategy;
use Hop\Validator\Strategy\StructureField;
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

    public function test_instanceOf(): void
    {
        $this->assertInstanceOf(Validator::class, $this->validator);
    }

    public function test_notExisitingRule(): void
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

    public function test_fromConfig(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        StdValidator::fromConfig([
            'filter' => 'NotExistingValidator'
        ]);
        $this->assertInstanceOf(StdValidator::class, StdValidator::fromConfig([
            'Email' => Validator\Email::class
        ]));
    }

    public function test_passesValidation(): void
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

    public function test_notPassesValidation(): void
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
        $this->assertEquals([
            'param' => [
                'passes' => 'Error'
            ]
        ], $this->validator->getMessages($input, $strategyMock)->toArray());
    }

    public function test_notPassesArray(): void
    {
        $strategyMock = $this->createMock(Strategy::class);
        $field = new Field('param', true, null);
        $field->setIsArray(true);
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
            'param' => [
                'passes',
                'passes'
            ]
        ];

        $this->validator->registerRuleValidator('passes', $ruleValidatorMock);

        $this->assertFalse($this->validator->isValid($input, $strategyMock));
        $this->assertCount(2, $this->validator->getMessages($input, $strategyMock));
        $this->assertEquals([
            'param' => [[
                    'passes' => 'Error'
                ],
                [
                    'passes' => 'Error'
                ]
            ]
        ], $this->validator->getMessages($input, $strategyMock)->toArray());
    }

    public function test_notRequiredField(): void
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

        $input = [
            'param' => null
        ];

        $this->assertTrue($this->validator->isValid($input, $strategyMock));
        $this->assertCount(0, $this->validator->getMessages($input, $strategyMock));

        $input = [
            'param' => ''
        ];

        $this->assertTrue($this->validator->isValid($input, $strategyMock));
        $this->assertCount(0, $this->validator->getMessages($input, $strategyMock));
    }

    public function test_requiredField(): void
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

    public function test_excludeCondition(): void
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

    public function test_excludeConditionOff(): void
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

    public function test_arrayField(): void
    {
        $strategyMock = $this->createMock(Strategy::class);
        $field = new Field('param', true, null);
        $field->registerValidator('passes', null);
        $field->setIsArray(true);

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

        $this->assertFalse($this->validator->isValid($input, $strategyMock));

        $input = [
            'param' => [
                'passes',
                'passes'
            ]
        ];

        $this->assertTrue($this->validator->isValid($input, $strategyMock));
        $this->assertCount(0, $this->validator->getMessages($input, $strategyMock));
    }

    public function test_structureField(): void
    {
        $singleField1 = new Field('field1', true, null);
        $singleField1->registerValidator('Passes1', null);
        $singleField1->registerValidator('Passes2', null);

        $singleField2 = new Field('field2', true, null);
        $singleField2->registerValidator('Passes1', null);

        $nestedStrategy = $this->createMock(Strategy::class);
        $nestedStrategy->method('getFields')
            ->willReturn([
                $singleField1, $singleField2
            ]);

        $field1 = new StructureField('nestedField', true, null, false, $nestedStrategy);

        $nestedStrategy = $this->createMock(Strategy::class);
        $nestedStrategy->method('getFields')
            ->willReturn([
                $field1
            ]);

        $this->validator->registerRuleValidator('Passes1', $this->createValidtor());
        $this->validator->registerRuleValidator('Passes2', $this->createValidtor());

        $input = [
            'nestedField' => [
                'field1' => 'notPasses',
                'field2' => 'notPasses'
            ]
        ];
        $this->assertFalse($this->validator->isValid($input, $nestedStrategy));
        $this->assertEquals([
            'nestedField' => [
                'field1' => [
                    'Passes1' => 'Some error returned',
                    'Passes2' => 'Some error returned'
                ],
                'field2' => [
                    'Passes1' => 'Some error returned'
                ]
            ]
        ], $this->validator->getMessages($input, $nestedStrategy)->toArray());

        $input = [
            'nestedField' => [
                'field1' => 'notPasses',
                'field2' => 'passes'
            ]
        ];
        $this->assertFalse($this->validator->isValid($input, $nestedStrategy));
        $this->assertEquals([
            'nestedField' => [
                'field1' => [
                    'Passes1' => 'Some error returned',
                    'Passes2' => 'Some error returned'
                ]
            ]
        ], $this->validator->getMessages($input, $nestedStrategy)->toArray());
    }

    public function test_arrayStructureField(): void
    {
        $singleField1 = new Field('field1', true, null);
        $singleField1->registerValidator('Passes1', null);
        $singleField1->registerValidator('Passes2', null);
        $singleField1->setIsArray(true);

        $singleField2 = new Field('field2', true, null);
        $singleField2->registerValidator('Passes1', null);
        $singleField2->setIsArray(true);

        $nestedStrategy = $this->createMock(Strategy::class);
        $nestedStrategy->method('getFields')
            ->willReturn([
                $singleField1, $singleField2
            ]);

        $field1 = new StructureField('nestedField', true, null, true, $nestedStrategy);

        $nestedStrategy = $this->createMock(Strategy::class);
        $nestedStrategy->method('getFields')
            ->willReturn([
                $field1
            ]);

        $this->validator->registerRuleValidator('Passes1', $this->createValidtor());
        $this->validator->registerRuleValidator('Passes2', $this->createValidtor());

        $input = [
            'nestedField' => [
                1 => [
                    'field1' => ['notPasses', 'passes', 'notPasses'],
                    'field2' => ['notPasses']
                ],
                2 => [
                    'field1' => ['passes', 'passes', 'notPasses'],
                    'field2' => ['notPasses', 'passes', 'notPasses']
                ],
                3 => [
                    'field1' => ['passes'],
                    'field2' => ['passes']
                ]
            ]
        ];
        $this->assertFalse($this->validator->isValid($input, $nestedStrategy));
        $this->assertEquals([
            'nestedField' => [
                1 => [
                    'field1' => [
                        0 => [
                            'Passes1' => 'Some error returned',
                            'Passes2' => 'Some error returned'
                        ],
                        2 => [
                            'Passes1' => 'Some error returned',
                            'Passes2' => 'Some error returned'
                        ],
                    ],
                    'field2' => [
                        0 => [
                            'Passes1' => 'Some error returned',
                        ]
                    ],
                ],
                2 => [
                    'field1' => [
                        2 => [
                            'Passes1' => 'Some error returned',
                            'Passes2' => 'Some error returned'
                        ],
                    ],
                    'field2' => [
                        0 => [
                            'Passes1' => 'Some error returned',
                        ],
                        2 => [
                            'Passes1' => 'Some error returned',
                        ]
                    ],
                ],
            ]
        ], $this->validator->getMessages($input, $nestedStrategy)->toArray());
    }

    private function createValidtor(): Validator\RuleValidator
    {
        $validator = $this->createMock(Validator\RuleValidator::class);
        $validator->method('isValid')
            ->willReturnCallback(function ($input): bool {
                return $input === 'passes';
            });
        $validator->method('getMessage')
            ->willReturnCallback(function ($input) {
                return $input === 'passes' ? null : 'Some error returned';
            });
        return $validator;
    }
}
