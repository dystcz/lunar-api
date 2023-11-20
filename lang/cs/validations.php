<?php

return [

    'addresses' => [
        'company_in' => [
            'string' => 'IČO společnosti musí být řetězec.',
        ],
        'company_tin' => [
            'string' => 'Pole DIČ společnosti musí být řetězec.',
        ],
        'line_one' => [
            'required' => 'Pole první řádek je povinné.',
            'string' => 'Pole první řádek musí být řetězec.',
        ],
        'line_two' => [
            'string' => 'Pole druhý řádek musí být řetězec.',
        ],
        'line_three' => [
            'string' => 'Pole třetí řádek musí být řetězec.',
        ],
        'city' => [
            'required' => 'Pole město je povinné.',
            'string' => 'Pole město musí být řetězec.',
        ],
        'state' => [
            'string' => 'Pole stát musí být řetězec.',
        ],
        'postcode' => [
            'required' => 'Pole PSČ je povinné.',
            'string' => 'Pole PSČ musí být řetězec.',
        ],
        'delivery_instructions' => [
            'string' => 'Pole instrukce pro doručení musí být řetězec.',
        ],
        'contact_email' => [
            'string' => 'Pole kontaktní e-mail musí být řetězec.',
        ],
        'contact_phone' => [
            'string' => 'Pole kontaktní telefon musí být řetězec.',
        ],
        'shipping_default' => [
            'boolean' => 'Pole výchozí doručení musí být logická hodnota.',
        ],
        'billing_default' => [
            'boolean' => 'Pole výchozí fakturace musí být logická hodnota.',
        ],
        'meta' => [
            'array' => 'Pole meta musí být pole.',
        ],
    ],

    'carts' => [
        'create_user' => [
            'boolean' => 'Pole vytvořit uživatele musí být logická hodnota.',
        ],
        'meta' => [
            'array' => 'Pole meta musí být pole.',
        ],
        'coupon_code' => [
            'required' => 'Pole kód kupónu je povinné.',
            'string' => 'Pole kód kupónu musí být řetězec.',
            'invalid' => 'Kupón není platný nebo byl použit příliš mnohokrát.',
        ],
        'agree' => [
            'accepted' => 'Musíte souhlasit s obchodními podmínkami.',
        ],
        'shipping_option' => [
            'required' => 'Prosím vyberte možnost doručení.',
        ],
    ],

    'cart_addresses' => [
        'title' => [
            'string' => 'Pole titul musí být řetězec.',
        ],
        'first_name' => [
            'required' => 'Pole jméno je povinné.',
            'string' => 'Pole jméno musí být řetězec.',
        ],
        'last_name' => [
            'required' => 'Pole příjmení je povinné.',
            'string' => 'Pole příjmení musí být řetězec.',
        ],
        'company_name' => [
            'string' => 'Pole název společnosti musí být řetězec.',
        ],
        'company_in' => [
            'string' => 'Pole IČO společnosti musí být řetězec.',
        ],
        'company_tin' => [
            'string' => 'Pole DIČ společnosti musí být řetězec.',
        ],
        'line_one' => [
            'required' => 'Pole první řádek je povinné.',
            'string' => 'Pole první řádek musí být řetězec.',
        ],
        'line_two' => [
            'string' => 'Pole druhý řádek musí být řetězec.',
        ],
        'line_three' => [
            'string' => 'Pole třetí řádek musí být řetězec.',
        ],
        'city' => [
            'required' => 'Pole město je povinné.',
            'string' => 'Pole město musí být řetězec.',
        ],
        'state' => [
            'string' => 'Pole stát musí být řetězec.',
        ],
        'postcode' => [
            'required' => 'Pole PSČ je povinné.',
            'string' => 'Pole PSČ musí být řetězec.',
        ],
        'delivery_instructions' => [
            'string' => 'Pole instrukce pro doručení musí být řetězec.',
        ],
        'contact_email' => [
            'string' => 'Pole kontaktní e-mail musí být řetězec.',
        ],
        'contact_phone' => [
            'string' => 'Pole kontaktní telefon musí být řetězec.',
        ],
        'shipping_option' => [
            'string' => 'Pole možnost doručení musí být řetězec.',
        ],
        'address_type' => [
            'required' => 'Pole typ adresy je povinné.',
            'string' => 'Pole typ adresy musí být řetězec.',
            'in' => 'Pole typ adresy musí být jedno z: :values.',
        ],
    ],

    'cart_lines' => [
        'quantity' => [
            'integer' => 'Pole množství musí být celé číslo.',
        ],
        'purchasable_id' => [
            'required' => 'Pole ID položky je povinné.',
            'integer' => 'Pole ID položky musí být celé číslo.',
        ],
        'purchasable_type' => [
            'required' => 'Pole typ položky je povinné.',
            'string' => 'Pole typ položky musí být řetězec.',
        ],
        'meta' => [
            'array' => 'Pole meta musí být pole.',
        ],
    ],

    'customers' => [
        'title' => [
            'string' => 'Pole titul musí být řetězec.',
        ],
        'first_name' => [
            'string' => 'Pole jméno musí být řetězec.',
        ],
        'last_name' => [
            'string' => 'Pole příjmení musí být řetězec.',
        ],
        'company_name' => [
            'string' => 'Pole název společnosti musí být řetězec.',
        ],
        'vat_no' => [
            'string' => 'DIČ společnosti musí být řetězec.',
        ],
        'account_ref' => [
            'string' => 'IČO společnosti musí být řetězec.',
        ],
    ],

    'orders' => [
        'notes' => [
            'string' => 'Pole poznámky musí být řetězec.',
        ],
    ],

    'payments' => [
        'payment_method' => [
            'required' => 'Pole způsob platby je povinné.',
            'string' => 'Pole způsob platby musí být řetězec.',
            'in' => 'Pole způsob platby musí být jedno z: :types.',
        ],
        'meta' => [
            'array' => 'Pole meta musí být pole.',
        ],
    ],

    'shipping' => [
        'shipping_option' => [
            'required' => 'Prosím vyberte možnost doručení',
            'string' => 'Pole možnost doručení musí být řetězec.',
        ],
    ],
];
