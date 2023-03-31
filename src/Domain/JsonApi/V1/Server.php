<?php

namespace Dystcz\LunarApi\Domain\JsonApi\V1;

use Dystcz\LunarApi\Domain\Addresses\JsonApi\V1\AddressSchema;
use Dystcz\LunarApi\Domain\Brands\JsonApi\V1\BrandSchema;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartAddressSchema;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartLineSchema;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartSchema;
use Dystcz\LunarApi\Domain\CollectionGroups\JsonApi\V1\CollectionGroupSchema;
use Dystcz\LunarApi\Domain\Collections\JsonApi\V1\CollectionSchema;
use Dystcz\LunarApi\Domain\Countries\JsonApi\V1\CountrySchema;
use Dystcz\LunarApi\Domain\Currencies\JsonApi\V1\CurrencySchema;
use Dystcz\LunarApi\Domain\Customers\JsonApi\V1\CustomerSchema;
use Dystcz\LunarApi\Domain\JsonApi\Servers\Server as BaseServer;
use Dystcz\LunarApi\Domain\Media\JsonApi\V1\ImageSchema;
use Dystcz\LunarApi\Domain\Media\JsonApi\V1\MediaSchema;
use Dystcz\LunarApi\Domain\Media\JsonApi\V1\ThumbnailSchema;
use Dystcz\LunarApi\Domain\Orders\JsonApi\V1\OrderAddressSchema;
use Dystcz\LunarApi\Domain\Orders\JsonApi\V1\OrderLineSchema;
use Dystcz\LunarApi\Domain\Orders\JsonApi\V1\OrderSchema;
use Dystcz\LunarApi\Domain\Prices\JsonApi\V1\PriceSchema;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductAssociationSchema;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductSchema;
use Dystcz\LunarApi\Domain\ProductVariants\JsonApi\V1\ProductVariantSchema;
use Dystcz\LunarApi\Domain\Shipping\JsonApi\V1\ShippingOptionSchema;
use Dystcz\LunarApi\Domain\Urls\JsonApi\V1\DefaultUrlSchema;
use Dystcz\LunarApi\Domain\Urls\JsonApi\V1\UrlSchema;
use Illuminate\Support\Facades\Config;

class Server extends BaseServer
{
    /**
     * Set base server URI.
     */
    protected function setBaseUri(string $path = 'v1'): void
    {
        $prefix = Config::get('lunar-api.route_prefix');

        $this->baseUri = "/{$prefix}/{$path}";
    }

    /**
     * Bootstrap the server when it is handling an HTTP request.
     */
    public function serving(): void
    {
        // no-op
    }

    /**
     * Get the server's list of schemas.
     */
    protected function allSchemas(): array
    {
        return [
            BrandSchema::class,
            CollectionSchema::class,
            CollectionGroupSchema::class,
            CartSchema::class,
            CartLineSchema::class,
            CartAddressSchema::class,
            CustomerSchema::class,
            CountrySchema::class,
            CurrencySchema::class,
            AddressSchema::class,
            DefaultUrlSchema::class,
            ImageSchema::class,
            MediaSchema::class,
            PriceSchema::class,
            ProductAssociationSchema::class,
            ProductSchema::class,
            ProductVariantSchema::class,
            ThumbnailSchema::class,
            UrlSchema::class,
            OrderSchema::class,
            OrderAddressSchema::class,
            OrderLineSchema::class,
            ShippingOptionSchema::class,
            ...$this->getAdditionalServerSchemas(),
        ];
    }

    /**
     * Determine if the server is authorizable.
     */
    public function authorizable(): bool
    {
        // TODO: Write policies
        return true;
    }
}
