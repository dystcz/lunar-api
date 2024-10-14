<?php

use Dystcz\LunarApi\Support\Models\Actions\SchemaType;

/*
 * Lunar API domains configuration
 */
return [
    'auth' => [
        'notifications' => [
            'reset_password' => Illuminate\Auth\Notifications\ResetPassword::class,
            'verify_email' => Illuminate\Auth\Notifications\VerifyEmail::class,
        ],
    ],

    SchemaType::get(Lunar\Models\Contracts\Address::class) => [
        'model' => Dystcz\LunarApi\Domain\Addresses\Models\Address::class,
        'lunar_model' => Lunar\Models\Contracts\Address::class,
        'policy' => Dystcz\LunarApi\Domain\Addresses\Policies\AddressPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\Addresses\JsonApi\V1\AddressSchema::class,
        'resource' => Dystcz\LunarApi\Domain\Addresses\JsonApi\V1\AddressResource::class,
        'query' => Dystcz\LunarApi\Domain\Addresses\JsonApi\V1\AddressQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\Addresses\JsonApi\V1\AddressCollectionQuery::class,
        'routes' => Dystcz\LunarApi\Domain\Addresses\Http\Routing\AddressRouteGroup::class,
    ],

    SchemaType::get(Lunar\Models\Contracts\Attribute::class) => [
        'model' => Dystcz\LunarApi\Domain\Attributes\Models\Attribute::class,
        'lunar_model' => Lunar\Models\Contracts\Attribute::class,
        'policy' => Dystcz\LunarApi\Domain\Attributes\Policies\AttributePolicy::class,
        'schema' => Dystcz\LunarApi\Domain\Attributes\JsonApi\V1\AttributeSchema::class,
        'resource' => Dystcz\LunarApi\Domain\Attributes\JsonApi\V1\AttributeResource::class,
        'query' => Dystcz\LunarApi\Domain\Attributes\JsonApi\V1\AttributeQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\Attributes\JsonApi\V1\AttributeCollectionQuery::class,
        'routes' => null,
    ],

    SchemaType::get(Lunar\Models\Contracts\AttributeGroup::class) => [
        'model' => Dystcz\LunarApi\Domain\AttributeGroups\Models\AttributeGroup::class,
        'lunar_model' => Lunar\Models\Contracts\AttributeGroup::class,
        'policy' => Dystcz\LunarApi\Domain\AttributeGroups\Policies\AttributeGroupPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\AttributeGroups\JsonApi\V1\AttributeGroupSchema::class,
        'resource' => Dystcz\LunarApi\Domain\AttributeGroups\JsonApi\V1\AttributeGroupResource::class,
        'query' => Dystcz\LunarApi\Domain\AttributeGroups\JsonApi\V1\AttributeGroupQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\AttributeGroups\JsonApi\V1\AttributeGroupCollectionQuery::class,
        'routes' => null,
    ],

    SchemaType::get(Lunar\Models\Contracts\ProductAssociation::class) => [
        'model' => Dystcz\LunarApi\Domain\ProductAssociations\Models\ProductAssociation::class,
        'lunar_model' => Lunar\Models\Contracts\ProductAssociation::class,
        'policy' => Dystcz\LunarApi\Domain\ProductAssociations\Policies\ProductAssociationPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\ProductAssociations\JsonApi\V1\ProductAssociationSchema::class,
        'resource' => Dystcz\LunarApi\Domain\ProductAssociations\JsonApi\V1\ProductAssociationResource::class,
        'query' => Dystcz\LunarApi\Domain\ProductAssociations\JsonApi\V1\ProductAssociationQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\ProductAssociations\JsonApi\V1\ProductAssociationCollectionQuery::class,
        'routes' => null,
    ],

    SchemaType::get(Lunar\Models\Contracts\Brand::class) => [
        'model' => Dystcz\LunarApi\Domain\Brands\Models\Brand::class,
        'lunar_model' => Lunar\Models\Contracts\Brand::class,
        'policy' => Dystcz\LunarApi\Domain\Brands\Policies\BrandPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\Brands\JsonApi\V1\BrandSchema::class,
        'resource' => Dystcz\LunarApi\Domain\Brands\JsonApi\V1\BrandResource::class,
        'query' => Dystcz\LunarApi\Domain\Brands\JsonApi\V1\BrandQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\Brands\JsonApi\V1\BrandCollectionQuery::class,
        'routes' => Dystcz\LunarApi\Domain\Brands\Http\Routing\BrandRouteGroup::class,
    ],

    SchemaType::get(Lunar\Models\Contracts\CartAddress::class) => [
        'model' => Dystcz\LunarApi\Domain\CartAddresses\Models\CartAddress::class,
        'lunar_model' => Lunar\Models\Contracts\CartAddress::class,
        'policy' => Dystcz\LunarApi\Domain\CartAddresses\Policies\CartAddressPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\CartAddresses\JsonApi\V1\CartAddressSchema::class,
        'resource' => Dystcz\LunarApi\Domain\CartAddresses\JsonApi\V1\CartAddressResource::class,
        'query' => Dystcz\LunarApi\Domain\CartAddresses\JsonApi\V1\CartAddressQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\CartAddresses\JsonApi\V1\CartAddressCollectionQuery::class,
        'routes' => Dystcz\LunarApi\Domain\CartAddresses\Http\Routing\CartAddressRouteGroup::class,
    ],

    SchemaType::get(Lunar\Models\Contracts\CartLine::class) => [
        'model' => Dystcz\LunarApi\Domain\CartLines\Models\CartLine::class,
        'lunar_model' => Lunar\Models\Contracts\CartLine::class,
        'policy' => Dystcz\LunarApi\Domain\CartLines\Policies\CartLinePolicy::class,
        'schema' => Dystcz\LunarApi\Domain\CartLines\JsonApi\V1\CartLineSchema::class,
        'resource' => Dystcz\LunarApi\Domain\CartLines\JsonApi\V1\CartLineResource::class,
        'query' => Dystcz\LunarApi\Domain\CartLines\JsonApi\V1\CartLineQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\CartLines\JsonApi\V1\CartLineCollectionQuery::class,
        'routes' => Dystcz\LunarApi\Domain\CartLines\Http\Routing\CartLineRouteGroup::class,
    ],

    SchemaType::get(Lunar\Models\Contracts\Cart::class) => [
        'model' => Dystcz\LunarApi\Domain\Carts\Models\Cart::class,
        'lunar_model' => Lunar\Models\Contracts\Cart::class,
        'policy' => Dystcz\LunarApi\Domain\Carts\Policies\CartPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartSchema::class,
        'resource' => Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartResource::class,
        'query' => Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartCollectionQuery::class,
        'routes' => Dystcz\LunarApi\Domain\Carts\Http\Routing\CartRouteGroup::class,
    ],

    SchemaType::get(Lunar\Models\Contracts\Channel::class) => [
        'model' => Dystcz\LunarApi\Domain\Channels\Models\Channel::class,
        'lunar_model' => Lunar\Models\Contracts\Channel::class,
        'policy' => Dystcz\LunarApi\Domain\Channels\Policies\ChannelPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\Channels\JsonApi\V1\ChannelSchema::class,
        'resource' => Dystcz\LunarApi\Domain\Channels\JsonApi\V1\ChannelResource::class,
        'query' => Dystcz\LunarApi\Domain\Channels\JsonApi\V1\ChannelQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\Channels\JsonApi\V1\ChannelCollectionQuery::class,
        'routes' => Dystcz\LunarApi\Domain\Channels\Http\Routing\ChannelRouteGroup::class,
    ],

    SchemaType::get(Lunar\Models\Contracts\Collection::class) => [
        'model' => Dystcz\LunarApi\Domain\Collections\Models\Collection::class,
        'lunar_model' => Lunar\Models\Contracts\Collection::class,
        'policy' => Dystcz\LunarApi\Domain\Collections\Policies\CollectionPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\Collections\JsonApi\V1\CollectionSchema::class,
        'resource' => Dystcz\LunarApi\Domain\Collections\JsonApi\V1\CollectionResource::class,
        'query' => Dystcz\LunarApi\Domain\Collections\JsonApi\V1\CollectionQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\Collections\JsonApi\V1\CollectionCollectionQuery::class,
        'routes' => Dystcz\LunarApi\Domain\Collections\Http\Routing\CollectionRouteGroup::class,
    ],

    SchemaType::get(Lunar\Models\Contracts\CollectionGroup::class) => [
        'model' => Dystcz\LunarApi\Domain\CollectionGroups\Models\CollectionGroup::class,
        'lunar_model' => Lunar\Models\Contracts\CollectionGroup::class,
        'policy' => Dystcz\LunarApi\Domain\CollectionGroups\Policies\CollectionGroupPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\CollectionGroups\JsonApi\V1\CollectionGroupSchema::class,
        'resource' => Dystcz\LunarApi\Domain\CollectionGroups\JsonApi\V1\CollectionGroupResource::class,
        'query' => Dystcz\LunarApi\Domain\CollectionGroups\JsonApi\V1\CollectionGroupQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\CollectionGroups\JsonApi\V1\CollectionGroupCollectionQuery::class,
        'routes' => null,
    ],

    SchemaType::get(Lunar\Models\Contracts\Country::class) => [
        'model' => Dystcz\LunarApi\Domain\Countries\Models\Country::class,
        'lunar_model' => Lunar\Models\Contracts\Country::class,
        'policy' => Dystcz\LunarApi\Domain\Countries\Policies\CountryPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\Countries\JsonApi\V1\CountrySchema::class,
        'resource' => Dystcz\LunarApi\Domain\Countries\JsonApi\V1\CountryResource::class,
        'query' => Dystcz\LunarApi\Domain\Countries\JsonApi\V1\CountryQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\Countries\JsonApi\V1\CountryCollectionQuery::class,
        'routes' => Dystcz\LunarApi\Domain\Countries\Http\Routing\CountryRouteGroup::class,
    ],

    SchemaType::get(Lunar\Models\Contracts\Currency::class) => [
        'model' => Dystcz\LunarApi\Domain\Currencies\Models\Currency::class,
        'lunar_model' => Lunar\Models\Contracts\Currency::class,
        'policy' => Dystcz\LunarApi\Domain\Currencies\Policies\CurrencyPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\Currencies\JsonApi\V1\CurrencySchema::class,
        'resource' => Dystcz\LunarApi\Domain\Currencies\JsonApi\V1\CurrencyResource::class,
        'query' => Dystcz\LunarApi\Domain\Currencies\JsonApi\V1\CurrencyQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\Currencies\JsonApi\V1\CurrencyCollectionQuery::class,
        'routes' => Dystcz\LunarApi\Domain\Currencies\Http\Routing\CurrencyRouteGroup::class,
    ],

    SchemaType::get(Lunar\Models\Contracts\Customer::class) => [
        'model' => Dystcz\LunarApi\Domain\Customers\Models\Customer::class,
        'lunar_model' => Lunar\Models\Contracts\Customer::class,
        'policy' => Dystcz\LunarApi\Domain\Customers\Policies\CustomerPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\Customers\JsonApi\V1\CustomerSchema::class,
        'resource' => Dystcz\LunarApi\Domain\Customers\JsonApi\V1\CustomerResource::class,
        'query' => Dystcz\LunarApi\Domain\Customers\JsonApi\V1\CustomerQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\Customers\JsonApi\V1\CustomerCollectionQuery::class,
        'routes' => Dystcz\LunarApi\Domain\Customers\Http\Routing\CustomerRouteGroup::class,
    ],

    // 'discounts' => [
    //     'model' => Dystcz\LunarApi\Domain\Discounts\Models\Discount::class,
    //     'lunar_model' => Lunar\Models\Contracts\Discount::class,
    //     'policy' => Dystcz\LunarApi\Domain\Discounts\Policies\DiscountPolicy::class,
    //     'schema' => Dystcz\LunarApi\Domain\Discounts\JsonApi\V1\DiscountSchema::class,
    //     'resource' => Dystcz\LunarApi\Domain\Discounts\JsonApi\V1\DiscountResource::class,
    //     'query' => Dystcz\LunarApi\Domain\Discounts\JsonApi\V1\DiscountQuery::class,
    //     'collection_query' => Dystcz\LunarApi\Domain\Discounts\JsonApi\V1\DiscountCollectionQuery::class,
    //     'routes' => Dystcz\LunarApi\Domain\Discounts\Http\Routing\DiscountRouteGroup::class,
    //
    // ],

    SchemaType::get(Spatie\MediaLibrary\MediaCollections\Models\Media::class) => [
        'model' => Spatie\MediaLibrary\MediaCollections\Models\Media::class,
        'lunar_model' => null,
        'policy' => Dystcz\LunarApi\Domain\Media\Policies\MediaPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\Media\JsonApi\V1\MediaSchema::class,
        'resource' => Dystcz\LunarApi\Domain\Media\JsonApi\V1\MediaResource::class,
        'query' => Dystcz\LunarApi\Domain\Media\JsonApi\V1\MediaQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\Media\JsonApi\V1\MediaCollectionQuery::class,
        'routes' => Dystcz\LunarApi\Domain\Media\Http\Routing\MediaRouteGroup::class,
    ],

    SchemaType::get(Lunar\Models\Contracts\Order::class) => [
        'model' => Dystcz\LunarApi\Domain\Orders\Models\Order::class,
        'lunar_model' => Lunar\Models\Contracts\Order::class,
        'policy' => Dystcz\LunarApi\Domain\Orders\Policies\OrderPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\Orders\JsonApi\V1\OrderSchema::class,
        'resource' => Dystcz\LunarApi\Domain\Orders\JsonApi\V1\OrderResource::class,
        'query' => Dystcz\LunarApi\Domain\Orders\JsonApi\V1\OrderQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\Orders\JsonApi\V1\OrderCollectionQuery::class,
        'routes' => Dystcz\LunarApi\Domain\Orders\Http\Routing\OrderRouteGroup::class,
    ],

    SchemaType::get(Lunar\Models\Contracts\OrderAddress::class) => [
        'model' => Dystcz\LunarApi\Domain\OrderAddresses\Models\OrderAddress::class,
        'lunar_model' => Lunar\Models\Contracts\OrderAddress::class,
        'policy' => Dystcz\LunarApi\Domain\OrderAddresses\Policies\OrderAddressPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\OrderAddresses\JsonApi\V1\OrderAddressSchema::class,
        'resource' => Dystcz\LunarApi\Domain\OrderAddresses\JsonApi\V1\OrderAddressResource::class,
        'query' => Dystcz\LunarApi\Domain\OrderAddresses\JsonApi\V1\OrderAddressQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\OrderAddresses\JsonApi\V1\OrderAddressCollectionQuery::class,
        'routes' => null,
    ],

    SchemaType::get(Lunar\Models\Contracts\OrderLine::class) => [
        'model' => Dystcz\LunarApi\Domain\OrderLines\Models\OrderLine::class,
        'lunar_model' => Lunar\Models\Contracts\OrderLine::class,
        'policy' => Dystcz\LunarApi\Domain\OrderLines\Policies\OrderLinePolicy::class,
        'schema' => Dystcz\LunarApi\Domain\OrderLines\JsonApi\V1\OrderLineSchema::class,
        'resource' => Dystcz\LunarApi\Domain\OrderLines\JsonApi\V1\OrderLineResource::class,
        'query' => Dystcz\LunarApi\Domain\OrderLines\JsonApi\V1\OrderLineQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\OrderLines\JsonApi\V1\OrderLineCollectionQuery::class,
        'routes' => null,
    ],

    SchemaType::get(Dystcz\LunarApi\Domain\PaymentOptions\Entities\PaymentOption::class) => [
        'model' => null,
        'lunar_model' => null,
        'policy' => null,
        'schema' => Dystcz\LunarApi\Domain\PaymentOptions\JsonApi\V1\PaymentOptionSchema::class,
        'resource' => Dystcz\LunarApi\Domain\PaymentOptions\JsonApi\V1\PaymentOptionResource::class,
        'query' => Dystcz\LunarApi\Domain\PaymentOptions\JsonApi\V1\PaymentOptionQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\PaymentOptions\JsonApi\V1\PaymentOptionCollectionQuery::class,
        'routes' => Dystcz\LunarApi\Domain\PaymentOptions\Http\Routing\PaymentOptionRouteGroup::class,
    ],

    SchemaType::get(Lunar\Models\Contracts\Price::class) => [
        'model' => Dystcz\LunarApi\Domain\Prices\Models\Price::class,
        'lunar_model' => Lunar\Models\Contracts\Price::class,
        'policy' => Dystcz\LunarApi\Domain\Prices\Policies\PricePolicy::class,
        'schema' => Dystcz\LunarApi\Domain\Prices\JsonApi\V1\PriceSchema::class,
        'resource' => Dystcz\LunarApi\Domain\Prices\JsonApi\V1\PriceResource::class,
        'query' => Dystcz\LunarApi\Domain\Prices\JsonApi\V1\PriceQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\Prices\JsonApi\V1\PriceCollectionQuery::class,
        'routes' => null,
    ],

    SchemaType::get(Lunar\Models\Contracts\ProductAssociation::class) => [
        'model' => Dystcz\LunarApi\Domain\ProductAssociations\Models\ProductAssociation::class,
        'lunar_model' => Lunar\Models\Contracts\ProductAssociation::class,
        'policy' => Dystcz\LunarApi\Domain\ProductAssociations\Policies\ProductAssociationPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\ProductAssociations\JsonApi\V1\ProductAssociationSchema::class,
        'resource' => Dystcz\LunarApi\Domain\ProductAssociations\JsonApi\V1\ProductAssociationResource::class,
        'query' => Dystcz\LunarApi\Domain\ProductAssociations\JsonApi\V1\ProductAssociationQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\ProductAssociations\JsonApi\V1\ProductAssociationCollectionQuery::class,
        'routes' => null,
    ],

    SchemaType::get(Lunar\Models\Contracts\ProductOption::class) => [
        'model' => Dystcz\LunarApi\Domain\ProductOptions\Models\ProductOption::class,
        'lunar_model' => Lunar\Models\Contracts\ProductOption::class,
        'policy' => Dystcz\LunarApi\Domain\ProductOptions\Policies\ProductOptionPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\ProductOptions\JsonApi\V1\ProductOptionSchema::class,
        'resource' => Dystcz\LunarApi\Domain\ProductOptions\JsonApi\V1\ProductOptionResource::class,
        'query' => Dystcz\LunarApi\Domain\ProductOptions\JsonApi\V1\ProductOptionQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\ProductOptions\JsonApi\V1\ProductOptionCollectionQuery::class,
        'routes' => null,
    ],

    SchemaType::get(Lunar\Models\Contracts\ProductOptionValue::class) => [
        'model' => Dystcz\LunarApi\Domain\ProductOptionValues\Models\ProductOptionValue::class,
        'lunar_model' => Lunar\Models\Contracts\ProductOptionValue::class,
        'policy' => Dystcz\LunarApi\Domain\ProductOptionValues\Policies\ProductOptionValuePolicy::class,
        'schema' => Dystcz\LunarApi\Domain\ProductOptionValues\JsonApi\V1\ProductOptionValueSchema::class,
        'resource' => Dystcz\LunarApi\Domain\ProductOptionValues\JsonApi\V1\ProductOptionValueResource::class,
        'query' => Dystcz\LunarApi\Domain\ProductOptionValues\JsonApi\V1\ProductOptionValueQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\ProductOptionValues\JsonApi\V1\ProductOptionValueCollectionQuery::class,
        'routes' => Dystcz\LunarApi\Domain\ProductOptionValues\Http\Routing\ProductOptionValueRouteGroup::class,
    ],

    SchemaType::get(Lunar\Models\Contracts\ProductType::class) => [
        'model' => Dystcz\LunarApi\Domain\ProductTypes\Models\ProductType::class,
        'lunar_model' => Lunar\Models\Contracts\ProductType::class,
        'policy' => Dystcz\LunarApi\Domain\ProductTypes\Policies\ProductTypePolicy::class,
        'schema' => Dystcz\LunarApi\Domain\ProductTypes\JsonApi\V1\ProductTypeSchema::class,
        'resource' => Dystcz\LunarApi\Domain\ProductTypes\JsonApi\V1\ProductTypeResource::class,
        'query' => Dystcz\LunarApi\Domain\ProductTypes\JsonApi\V1\ProductTypeQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\ProductTypes\JsonApi\V1\ProductTypeCollectionQuery::class,
        'routes' => null,
    ],

    SchemaType::get(Lunar\Models\Contracts\Product::class) => [
        'model' => Dystcz\LunarApi\Domain\Products\Models\Product::class,
        'lunar_model' => Lunar\Models\Contracts\Product::class,
        'policy' => Dystcz\LunarApi\Domain\Products\Policies\ProductPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductSchema::class,
        'resource' => Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductResource::class,
        'query' => Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductCollectionQuery::class,
        'routes' => Dystcz\LunarApi\Domain\Products\Http\Routing\ProductRouteGroup::class,
    ],

    SchemaType::get(Lunar\Models\Contracts\ProductVariant::class) => [
        'model' => Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant::class,
        'lunar_model' => Lunar\Models\Contracts\ProductVariant::class,
        'policy' => Dystcz\LunarApi\Domain\ProductVariants\Policies\ProductVariantPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\ProductVariants\JsonApi\V1\ProductVariantSchema::class,
        'resource' => Dystcz\LunarApi\Domain\ProductVariants\JsonApi\V1\ProductVariantResource::class,
        'query' => Dystcz\LunarApi\Domain\ProductVariants\JsonApi\V1\ProductVariantQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\ProductVariants\JsonApi\V1\ProductVariantCollectionQuery::class,
        'routes' => Dystcz\LunarApi\Domain\ProductVariants\Http\Routing\ProductVariantRouteGroup::class,
    ],

    SchemaType::get(Dystcz\LunarApi\Domain\ShippingOptions\Entities\ShippingOption::class) => [
        'model' => null,
        'lunar_model' => null,
        'policy' => null,
        'schema' => Dystcz\LunarApi\Domain\ShippingOptions\JsonApi\V1\ShippingOptionSchema::class,
        'resource' => Dystcz\LunarApi\Domain\ShippingOptions\JsonApi\V1\ShippingOptionResource::class,
        'query' => Dystcz\LunarApi\Domain\ShippingOptions\JsonApi\V1\ShippingOptionQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\ShippingOptions\JsonApi\V1\ShippingOptionCollectionQuery::class,
        'routes' => Dystcz\LunarApi\Domain\ShippingOptions\Http\Routing\ShippingOptionRouteGroup::class,
    ],

    SchemaType::get(Lunar\Models\Contracts\Tag::class) => [
        'model' => Dystcz\LunarApi\Domain\Tags\Models\Tag::class,
        'lunar_model' => Lunar\Models\Contracts\Tag::class,
        'policy' => Dystcz\LunarApi\Domain\Tags\Policies\TagPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\Tags\JsonApi\V1\TagSchema::class,
        'resource' => Dystcz\LunarApi\Domain\Tags\JsonApi\V1\TagResource::class,
        'query' => Dystcz\LunarApi\Domain\Tags\JsonApi\V1\TagQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\Tags\JsonApi\V1\TagCollectionQuery::class,
        'routes' => Dystcz\LunarApi\Domain\Tags\Http\Routing\TagRouteGroup::class,
    ],

    SchemaType::get(Lunar\Models\Contracts\Transaction::class) => [
        'model' => Dystcz\LunarApi\Domain\Transactions\Models\Transaction::class,
        'lunar_model' => Lunar\Models\Contracts\Transaction::class,
        'policy' => Dystcz\LunarApi\Domain\Transactions\Policies\TransactionPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\Transactions\JsonApi\V1\TransactionSchema::class,
        'resource' => Dystcz\LunarApi\Domain\Transactions\JsonApi\V1\TransactionResource::class,
        'query' => Dystcz\LunarApi\Domain\Transactions\JsonApi\V1\TransactionQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\Transactions\JsonApi\V1\TransactionCollectionQuery::class,
        'routes' => null,
    ],

    SchemaType::get(Lunar\Models\Contracts\Url::class) => [
        'model' => Dystcz\LunarApi\Domain\Urls\Models\Url::class,
        'lunar_model' => Lunar\Models\Contracts\Url::class,
        'policy' => Dystcz\LunarApi\Domain\Urls\Policies\UrlPolicy::class,
        'schema' => Dystcz\LunarApi\Domain\Urls\JsonApi\V1\UrlSchema::class,
        'resource' => Dystcz\LunarApi\Domain\Urls\JsonApi\V1\UrlResource::class,
        'query' => Dystcz\LunarApi\Domain\Urls\JsonApi\V1\UrlQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\Urls\JsonApi\V1\UrlCollectionQuery::class,
        'routes' => Dystcz\LunarApi\Domain\Urls\Http\Routing\UrlRouteGroup::class,
    ],

    SchemaType::get(Lunar\Models\Contracts\TaxZone::class) => [
        'model' => Dystcz\LunarApi\Domain\TaxZones\Models\TaxZone::class,
        'lunar_model' => Lunar\Models\Contracts\TaxZone::class,
        'policy' => Dystcz\LunarApi\Domain\TaxZones\Policies\TaxZonePolicy::class,
        'schema' => Dystcz\LunarApi\Domain\TaxZones\JsonApi\V1\TaxZoneSchema::class,
        'resource' => Dystcz\LunarApi\Domain\TaxZones\JsonApi\V1\TaxZoneResource::class,
        'query' => Dystcz\LunarApi\Domain\TaxZones\JsonApi\V1\TaxZoneQuery::class,
        'collection_query' => Dystcz\LunarApi\Domain\TaxZones\JsonApi\V1\TaxZoneCollectionQuery::class,
        'routes' => null,
    ],
];
