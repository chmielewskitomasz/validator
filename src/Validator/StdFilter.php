<?php

declare(strict_types = 1);

namespace Hop\Validator;

use Hop\Validator\Filter\RuleFilter;
use Hop\Validator\Strategy\Strategy;

/**
 * Class StdFilter
 * @package Hop\Validator
 */
class StdFilter implements Filter
{
    /**
     * @var RuleFilter[]
     */
    private $filters = [];

    /**
     * @param array $config
     * @return StdFilter
     * @throws \InvalidArgumentException
     */
    public static function fromConfig(array $config): self
    {
        $filter = new static;
        foreach ($config as $filterName => $filterClassName) {
            if (!\class_exists($filterClassName)) {
                throw new \InvalidArgumentException(sprintf('Filter class %s does not exist', $filterClassName));
            }
            $filter->registerRuleFilter($filterName, new $filterClassName);
        }
        return $filter;
    }

    /**
     * @param array $data
     * @param Strategy $strategy
     * @return array
     */
    public function filter(array $data, Strategy $strategy): array
    {
        $filtered = [];
        foreach ($strategy->getFields() as $field) {
            $fieldName = $field->fieldName();
            if (!\array_key_exists($fieldName, $data)) {
                continue;
            }

            $filtered[$fieldName] = $data[$fieldName];
            foreach ($field->filters() as $filter => $options) {
                $filtered[$fieldName] = $this->getRuleFilter($filter)->filter($filtered[$fieldName], $options);
            }
        }
        return $filtered;
    }

    /**
     * @param string $filterName
     * @param RuleFilter $filter
     */
    public function registerRuleFilter(string $filterName, RuleFilter $filter): void
    {
        $this->filters[$filterName] = $filter;
    }

    /**
     * @param string $filterName
     * @return RuleFilter
     * @throws \InvalidArgumentException
     */
    public function getRuleFilter(string $filterName): RuleFilter
    {
        if (!\array_key_exists($filterName, $this->filters)) {
            throw new \InvalidArgumentException(sprintf('Filter %s not found', $filterName));
        }
        return $this->filters[$filterName];
    }
}
