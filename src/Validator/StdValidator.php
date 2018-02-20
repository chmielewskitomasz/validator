<?php

declare(strict_types = 1);

namespace Hop\Validator;

use Hop\Validator\Strategy\Strategy;
use Hop\Validator\Validator\RuleValidator;

/**
 * Class StdValidator
 * @package Application\Service\Validator
 */
class StdValidator implements Validator
{
    /**
     * @var RuleValidator[]
     */
    private $validators = [];

    /**
     * @param string $validatorName
     * @param RuleValidator $validator
     */
    public function registerRuleValidator(string $validatorName, RuleValidator $validator): void
    {
        $this->validators[$validatorName] = $validator;
    }

    /**
     * @param array $config
     * @return StdValidator
     */
    public static function fromConfig(array $config): self
    {
        $validator = new static;
        foreach ($config as $validatorName => $validatorClassName) {
            if (!\class_exists($validatorClassName)) {
                throw new \InvalidArgumentException(sprintf('Validator class %s does not exist', $validatorClassName));
            }
            $validator->registerRuleValidator($validatorName, new $validatorClassName);
        }
        return $validator;
    }

    /**
     * @param array $data
     * @param Strategy $strategy
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isValid(array $data, Strategy $strategy): bool
    {
        return \count($this->getMessages($data, $strategy)) === 0;
    }

    /**
     * @param array $data
     * @param Strategy $strategy
     * @return Messages
     * @throws \InvalidArgumentException
     */
    public function getMessages(array $data, Strategy $strategy): Messages
    {
        $messages = new Messages;

        foreach ($strategy->getFields() as $field) {
            if ($field->condition() !== null && !$field->condition()($data)) {
                continue;
            }

            if ($this->detectEmpty($data, $field->fieldName())) {
                if (!$field->required()) {
                    continue;
                }
                $messages->attachMessage($field->fieldName(), new Message('NotEmpty', 'Value is required and cannot be empty'));
            }

            foreach ($field->validators() as $validatorName => $options) {
                $ruleValidator = $this->getRuleValidator($validatorName);
                if (!$ruleValidator->isValid($data[$field->fieldName()], $options)) {
                    $messages->attachMessage($field->fieldName(), new Message($validatorName, $ruleValidator->getMessage($data[$field->fieldName()], $options)));
                }
            }
        }
        return $messages;
    }

    /**
     * @param string $name
     * @return RuleValidator
     * @throws \InvalidArgumentException
     */
    private function getRuleValidator(string $name): RuleValidator
    {
        if (!isset($this->validators[$name])) {
            throw new \InvalidArgumentException(sprintf('Validator %s not registered', $name));
        }

        return $this->validators[$name];
    }

    /**
     * @param array $data
     * @param string $fieldName
     * @return bool
     */
    private function detectEmpty(array $data, string $fieldName): bool
    {
        if (!array_key_exists($fieldName, $data)) {
            return true;
        }

        if ($data[$fieldName] === null) {
            return true;
        }

        if (\is_string($data[$fieldName]) && $data[$fieldName] === '') {
            return true;
        }

        return false;
    }
}
