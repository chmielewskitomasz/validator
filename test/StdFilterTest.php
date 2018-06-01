<?php

declare(strict_types = 1);

namespace Test;

use Hop\Validator\Filter;
use Hop\Validator\StdFilter;
use Hop\Validator\Strategy\Field;
use Hop\Validator\Strategy\Strategy;
use Hop\Validator\Strategy\StructureField;
use Hop\Validator\Validator\Email;
use PHPUnit\Framework\TestCase;

class StdFilterTest extends TestCase
{
    /**
     * @var StdFilter
     */
    private $filter;

    public function setUp()
    {
        $this->filter = new StdFilter();
        $this->filter->registerRuleFilter('tolower', new class implements Filter\RuleFilter {
            public function filter($value, ?array $options)
            {
                return \strtolower($value);
            }
        });

        $this->filter->registerRuleFilter('striptags', new class implements Filter\RuleFilter {
            public function filter($value, ?array $options)
            {
                return \strip_tags($value);
            }
        });
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(Filter::class, $this->filter);
    }

    public function test_fromConfig(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        StdFilter::fromConfig([
            'filter' => 'NotExistingFilter'
        ]);
        $this->assertInstanceOf(StdFilter::class, StdFilter::fromConfig([
            'Email' => Filter\Sanitize::class
        ]));
    }

    public function test_registerRule()
    {
        $stub = $this->createMock(Filter\RuleFilter::class);
        $this->filter->registerRuleFilter('testFilter', $stub);
        $this->assertEquals($stub, $this->filter->getRuleFilter('testFilter'));
    }

    public function test_notExistingRule()
    {
        $this->expectException(\InvalidArgumentException::class);
        $stub = $this->createMock(Filter\RuleFilter::class);
        $this->filter->registerRuleFilter('testFilter', $stub);
        $this->assertEquals($stub, $this->filter->getRuleFilter('notExistingTestFilter'));
    }

    public function test_filter()
    {
        $data = [
            'string1' => '<tag>TAG</tag>',
            'string2' => 'Nothing happened'
        ];

        $strategy = new class implements Strategy {
            public function getFields(): array
            {
                $field1 = new Field('string1', true, null);
                $field1->registerFilter('tolower', null);
                $field1->registerFilter('striptags', null);

                $field2 = new Field('string2', true, null);

                return [
                    $field1, $field2
                ];
            }
        };

        $filtered = $this->filter->filter($data, $strategy);
        $this->assertEquals(['string1' => 'tag', 'string2' => 'Nothing happened'], $filtered);
    }

    public function test_filterArray(): void
    {
        $data = [
            'string1' => [
                '<tag>TAG</tag>',
                '<strong>Tag</strong>',
            ],
            'string2' => [
                'Nothing happened'
            ]
        ];

        $strategy = new class implements Strategy {
            public function getFields(): array
            {
                $field1 = new Field('string1', true, null);
                $field1->registerFilter('tolower', null);
                $field1->registerFilter('striptags', null);
                $field1->setIsArray(true);

                $field2 = new Field('string2', true, null);
                $field2->setIsArray(true);

                return [
                    $field1, $field2
                ];
            }
        };

        $filtered = $this->filter->filter($data, $strategy);
        $this->assertEquals([
            'string1' => ['tag', 'tag'],
            'string2' => ['Nothing happened']
        ], $filtered);
    }

    public function test_nested(): void
    {
        $data = [
            'nested' => [
                'string1' => '<tag>TAG</tag>',
                'string2' => 'Nothing happened'
        ]];

        $strategy = new class implements Strategy {
            public function getFields(): array
            {
                $field1 = new Field('string1', true, null);
                $field1->registerFilter('tolower', null);
                $field1->registerFilter('striptags', null);

                $field2 = new Field('string2', true, null);

                return [
                    $field1, $field2
                ];
            }
        };

        $structureField = new StructureField('nested', true, null, false, $strategy);
        $nestedStrategy = $this->createMock(Strategy::class);
        $nestedStrategy->method('getFields')
            ->willReturn([$structureField]);

        $filtered = $this->filter->filter($data, $nestedStrategy);
        $this->assertEquals([
            'nested' => [
                'string1' => 'tag',
                'string2' => 'Nothing happened'
            ]], $filtered);
    }

    public function test_nestedArray(): void
    {
        $data = [
            'nested' => [[
                'string1' => [
                    '<tag>TAG</tag>',
                    '<strong>Tag</strong>',
                ],
                'string2' => [
                    'Nothing happened'
                ]
            ], 3 => [
                'string1' => [
                    '<tag>ZZZzz</tag>',
                    '<strong>aaSSS</strong>',
                ],
                'string2' => [
                    'Nothing happened'
                ]
            ]]
        ];

        $strategy = new class implements Strategy {
            public function getFields(): array
            {
                $field1 = new Field('string1', true, null);
                $field1->registerFilter('tolower', null);
                $field1->registerFilter('striptags', null);
                $field1->setIsArray(true);

                $field2 = new Field('string2', true, null);
                $field2->setIsArray(true);

                return [
                    $field1, $field2
                ];
            }
        };

        $structureField = new StructureField('nested', true, null, true, $strategy);
        $nestedStrategy = $this->createMock(Strategy::class);
        $nestedStrategy->method('getFields')
            ->willReturn([$structureField]);

        $filtered = $this->filter->filter($data, $nestedStrategy);
        $this->assertEquals([
            'nested' => [[
                'string1' => [
                    'tag',
                    'tag',
                ],
                'string2' => [
                    'Nothing happened'
                ]
            ], 3 => [
                'string1' => [
                    'zzzzz',
                    'aasss',
                ],
                'string2' => [
                    'Nothing happened'
                ]
            ]]
        ], $filtered);
    }
}
