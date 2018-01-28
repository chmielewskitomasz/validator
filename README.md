## Simple, lightweight input validation lib for PHP

#### How to install it?

```
composer require chmielewskitomasz/validator
```

#### How to use it?
```php
require_once __FILE__ . '/vendor/autoload.php';

use Hop\Strategy\Strategy;
use Hop\Strategy\Field;
use Hop\StdValidator;

// first, create your input validation strategy
class AlbumInputValidator implements Strategy
{
    /**
     * @inheritdoc
     */
    public function getFields(): array
    {
        $nameField = new Field(
            'name',
            true,
            null
        );
        
        $phoneField = new Field(
            'phone'
            false,
            null
        );
        
        $phoneField->registerValidator('Digits');
        $phoneField->registerValidator('Length', ['min' => 9, 'max' => 9]);
    
        return [
            $nameField,
            $phoneField
        ];
    }
}

// second, create stdValidator
$validator = StdValidator::fromConfig(include __DIR__ . '/config/validators.php');


// three - validate your input
$myInput = [
    'name' => 'Tom',
    'phone' => '999888000'
];
$validator->isValid($myInput, new AlbumInputValidator());  // true
$validator->getMessages($myInput, new AlbumInputValidator());  // null


$myWrongInput = [
    'name' => 'Tom',
    'phone' => 'thisShouldBeA9DigitsNumber'
];
$validator->isValid($myWrongInput, new AlbumInputValidator()) // false
$validator->getMessages($myWrongInput, new AlbumInputValidato()) 
//  ['phone' => Message()]
```

#### Ok, I want my field to be optional, but if it is passed I want to validate it
```php
//    In strategy: 
//    ...
    public function getFields(): array
    {
        $field = new Field(
            'phone',
            false, // set second parameter 'required' as false,
            null
        );
        
        $field->registerValidator('Digits', null);
        
        return [$field];
    }
//    ...


// and then
$validator->isValid([], new AlbumInputValidator()); // true
$validator->isValid(['phone' => 'abcdefg'], new AlbumInputValidator()); // false
```

#### I want a single field to be conditional. I mean, if eg street name is not passed, I don't want house number to be required.
Have a look:

```php
//    In strategy: 
//    ...
    public function getFields(): array
    {
        $streetField = new Field(
            'street',
            false,
            null
        );
        
        $houseNumberField = new Field(
            'houseNumber',
            true,
            function (array $inputData) {
                return !isset($inputData['street']);
            }
        );
        
        return [$streetField, $houseNumberField];
    }
//    ...


// and then
$validator->isValid(['street' => 'High st.'], new AlbumInputValidator()); // false
$validator->isValid([], new AlbumInputValidator()); // true
$validator->isValid(['street' => 'High st.', 'houseNumber' => '12'], new AlbumInputValidator()); // true
```

#### How can I implement my own validators?

Easily. You can help me to contribute this project. Or if you don't want to, just create a class implementing `Hop\Validator\Strategy\Strategy` and pass it 
to `StdValidator::fromConfig()` as array, eg
```php
$myValidators = [
    'MyValidator' => 'Path\To\Validator\Class'
];

$validator = StdValidator::fromConfig(
    \array_merge(include __DIR__ . '/config/validators.php', $myValidators)
);
```

&copy; Chmielewski Tomasz