<?php

return [

    'auth' => [
        'email' => [
            'required' => 'Please enter your email address.',
            'email' => 'Please enter a valid email address.',
            'unique' => 'This email address is already in use.',
            'max' => 'Your email address must be less than :max characters long.',
        ],

        'password' => [
            'required' => 'Please enter a password.',
            'min' => 'Your password must be at least :min characters long.',
            'confirmed' => 'Please confirm your password.',
        ],
    ],

    'addresses' => [
        'first_name' => [
            'required' => 'First name field is required.',
            'string' => 'First name field must be a string.',
        ],
        'last_name' => [
            'required' => 'Last name field is required.',
            'string' => 'Last name field must be a string.',
        ],
        'company_in' => [
            'string' => 'Company ID must be a string.',
        ],
        'company_tin' => [
            'string' => 'Company tax ID field must be a string.',
        ],
        'line_one' => [
            'required' => 'Line one field is required.',
            'string' => 'Line one field must be a string.',
        ],
        'line_two' => [
            'string' => 'Line two field must be a string.',
        ],
        'line_three' => [
            'string' => 'Line three field must be a string.',
        ],
        'city' => [
            'required' => 'City field is required.',
            'string' => 'City field must be a string.',
        ],
        'state' => [
            'string' => 'State field must be a string.',
        ],
        'postcode' => [
            'required' => 'Postcode field is required.',
            'string' => 'Postcode field must be a string.',
        ],
        'delivery_instructions' => [
            'string' => 'Delivery instructions field must be a string.',
        ],
        'contact_email' => [
            'string' => 'Contact email field must be a string.',
        ],
        'contact_phone' => [
            'string' => 'Contact phone field must be a string.',
        ],
        'shipping_default' => [
            'boolean' => 'Shipping default field must be a boolean.',
        ],
        'billing_default' => [
            'boolean' => 'Billing default field must be a boolean.',
        ],
        'meta' => [
            'array' => 'Meta field must be an array.',
        ],
    ],

    'carts' => [
        'create_user' => [
            'boolean' => 'Create user field must be a boolean.',
        ],
        'meta' => [
            'array' => 'Meta field must be an array.',
        ],
        'coupon_code' => [
            'required' => 'Coupon code field is required.',
            'string' => 'Coupon code field must be a string.',
            'invalid' => 'The coupon is not valid or has been used too many times',
        ],
        'agree' => [
            'accepted' => 'You must agree to the terms and conditions.',
        ],
        'shipping_option' => [
            'required' => 'Please select a shipping option.',
        ],
        'payment_option' => [
            'required' => 'Please select a payment option.',
        ],
        'products' => [
            'out_of_stock' => 'Not all products are available in the required quantity.',
        ],
    ],

    'cart_addresses' => [
        'title' => [
            'string' => 'Title field must be a string.',
        ],
        'first_name' => [
            'required' => 'First name field is required.',
            'string' => 'First name field must be a string.',
        ],
        'last_name' => [
            'required' => 'Last name field is required.',
            'string' => 'Last name field must be a string.',
        ],
        'company_name' => [
            'string' => 'Company name field must be a string.',
        ],
        'company_in' => [
            'string' => 'Company in field must be a string.',
        ],
        'company_tin' => [
            'string' => 'Company tin field must be a string.',
        ],
        'line_one' => [
            'required' => 'Line one field is required.',
            'string' => 'Line one field must be a string.',
        ],
        'line_two' => [
            'string' => 'Line two field must be a string.',
        ],
        'line_three' => [
            'string' => 'Line three field must be a string.',
        ],
        'city' => [
            'required' => 'City field is required.',
            'string' => 'City field must be a string.',
        ],
        'state' => [
            'string' => 'State field must be a string.',
        ],
        'postcode' => [
            'required' => 'Postcode field is required.',
            'string' => 'Postcode field must be a string.',
        ],
        'delivery_instructions' => [
            'string' => 'Delivery instructions field must be a string.',
        ],
        'contact_email' => [
            'string' => 'Contact email field must be a string.',
        ],
        'contact_phone' => [
            'string' => 'Contact phone field must be a string.',
        ],
        'shipping_option' => [
            'string' => 'Shipping option field must be a string.',
        ],
        'address_type' => [
            'required' => 'Address type field is required.',
            'string' => 'Address type field must be a string.',
            'in' => 'Address type field must be one of: :values.',
        ],
    ],

    'cart_lines' => [
        'quantity' => [
            'integer' => 'Quantity field must be an integer.',
        ],
        'purchasable_id' => [
            'required' => 'Purchasable id field is required.',
            'integer' => 'Purchasable id field must be an integer.',
        ],
        'purchasable_type' => [
            'required' => 'Purchasable type field is required.',
            'string' => 'Purchasable type field must be a string.',
        ],
        'meta' => [
            'array' => 'Meta field must be an array.',
        ],
    ],

    'customers' => [
        'title' => [
            'string' => 'Title field must be a string.',
        ],
        'first_name' => [
            'string' => 'First name field must be a string.',
        ],
        'last_name' => [
            'string' => 'Last name field must be a string.',
        ],
        'company_name' => [
            'string' => 'Company name field must be a string.',
        ],
        'vat_no' => [
            'string' => 'Company tax ID must be a string.',
        ],
        'account_ref' => [
            'string' => 'Company ID must be a string.',
        ],
    ],

    'orders' => [
        'notes' => [
            'string' => 'Notes field must be a string.',
        ],
    ],

    'payments' => [
        'payment_method' => [
            'required' => 'Payment method field is required.',
            'string' => 'Payment method field must be a string.',
            'in' => 'Payment method field must be one of: :types.',
        ],
        'amount' => [
            'numeric' => 'Amount field must be numeric.',
        ],
        'meta' => [
            'array' => 'Meta field must be an array.',
        ],
    ],

    'shipping' => [
        'set_shipping_option' => [
            'shipping_option' => [
                'required' => 'Please select a shipping option.',
                'string' => 'Shipping option field must be a string.',
            ],
        ],
    ],

    'payments' => [
        'set_payment_option' => [
            'payment_option' => [
                'required' => 'Please select a payment method.',
                'string' => 'Payment method field must be a string.',
            ],
        ],
    ],
];
