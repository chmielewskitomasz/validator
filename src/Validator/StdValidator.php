<?php

declare(strict_types = 1);

namespace Hop\Validator;

use Hop\Validator\Message\MessageInterface;
use Hop\Validator\Message\FieldMessage;
use Hop\Validator\Message\MessagesContainer;
use Hop\Validator\Strategy\Field;
use Hop\Validator\Strategy\FieldInterface;
use Hop\Validator\Strategy\Strategy;
use Hop\Validator\Strategy\StructureField;
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
     * @return \Hop\Validator\Message\MessagesContainer
     */
    public function getMessages(array $data, Strategy $strategy): MessagesContainer
    {
        $messages = new MessagesContainer();

        foreach ($strategy->getFields() as $field) {
            if ($field->condition() !== null && !$field->condition()($data)) {
                continue;
            }

            if ($this->detectEmpty($data, $field->fieldName())) {
                if (!$field->required()) {
                    continue;
                }
                $messages->attachMessage($field->fieldName(), (new FieldMessage())->attachMessage('NotEmpty', 'Value cannot be empty'));
            }

            $messages->attachMessage(
                $field->fieldName(),
                $field->isArray() ?
                    $this->processArrayField($field, $data[$field->fieldName()]) :
                    $this->processSingleField($data[$field->fieldName()], $field)
            );
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

    private function processSingleField($row, FieldInterface $field): MessageInterface
    {
        // TODO introduce policies/strategies if required
        // for now it might be overengineering
        switch (true) {
            case $field instanceof Field:
                return $this->processField($field, $row);
                break;
            case $field instanceof StructureField:
                return $this->getMessages($row, $field->strategy());
                break;
            default:
                throw new \DomainException('Unknown field type');
        }
    }

    private function processField(Field $field, $row): FieldMessage
    {
        $messages = new FieldMessage();
        foreach ($field->validators() as $validatorName => $options) {
            $ruleValidator = $this->getRuleValidator($validatorName);
            if (!$ruleValidator->isValid($row, $options)) {
                $messages->attachMessage($validatorName, $ruleValidator->getMessage($row, $options));
            }
        }
        return $messages;
    }

    private function processArrayField(FieldInterface $field, $data) : MessageInterface
    {
        $messages = new MessagesContainer();
        if (!\is_array($data)) {
            return (new FieldMessage())->attachMessage('NotArray', 'Value is not an array');
        }

        foreach ($data as $index => $row) {
            $messages->attachMessage((string)$index, $this->processSingleField($row, $field));
        }
        return $messages;
    }
}
