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

            if (!array_key_exists($field->fieldName(), $data)) {
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
}
