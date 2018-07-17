<?php

declare(strict_types = 1);

return [
    'Digits' => \Hop\Validator\Validator\Digits::class,
    'Range' => \Hop\Validator\Validator\Range::class,
    'Length' => \Hop\Validator\Validator\Length::class,
    'NotEmpty' => \Hop\Validator\Validator\NotEmpty::class,
    'Email' => \Hop\Validator\Validator\Email::class,
    'Uuid' => \Hop\Validator\Validator\Uuid::class,
    'Nip' => \Hop\Validator\Validator\Nip::class,
    'InArray' => \Hop\Validator\Validator\InArray::class,
    'DateTime' => \Hop\Validator\Validator\DateTime::class,
    'Date' => \Hop\Validator\Validator\Date::class,
    'RgbHex' => \Hop\Validator\Validator\Color\Rgb\Hex::class,
];