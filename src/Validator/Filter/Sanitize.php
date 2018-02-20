<?php

declare(strict_types = 1);

namespace Hop\Validator\Filter;

/**
 * Class Sanitize
 * @package Hop\Validator\Filter
 */
class Sanitize implements RuleFilter
{
    /**
     * @param $value
     * @param array|null $options
     * @return mixed
     */
    public function filter($value, ?array $options)
    {
        return \filter_var($value, \FILTER_SANITIZE_STRING, \FILTER_FLAG_NO_ENCODE_QUOTES);
    }
}
