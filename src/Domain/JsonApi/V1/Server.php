<?php

namespace Dystcz\LunarApi\Domain\JsonApi\V1;

use Dystcz\LunarApi\Domain\Brands\JsonApi\V1\BrandSchema;
use Dystcz\LunarApi\Domain\CollectionGroups\JsonApi\V1\CollectionGroupSchema;
use Dystcz\LunarApi\Domain\Collections\JsonApi\V1\CollectionSchema;
use Dystcz\LunarApi\Domain\JsonApi\Servers\Server as BaseServer;
use Dystcz\LunarApi\Domain\Media\JsonApi\V1\ImageSchema;
use Dystcz\LunarApi\Domain\Media\JsonApi\V1\MediaSchema;
use Dystcz\LunarApi\Domain\Media\JsonApi\V1\ThumbnailSchema;
use Dystcz\LunarApi\Domain\Prices\JsonApi\V1\PriceSchema;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductAssociationSchema;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductSchema;
use Dystcz\LunarApi\Domain\ProductVariants\JsonApi\V1\ProductVariantSchema;
use Dystcz\LunarApi\Domain\Urls\JsonApi\V1\DefaultUrlSchema;
use Dystcz\LunarApi\Domain\Urls\JsonApi\V1\UrlSchema;
use Illuminate\Support\Facades\Config;

class Server extends BaseServer
{
    /**
     * Set base server URI.
     *
     * @param  string  $path
     * @return void
     */
    protected function setBaseUri(string $path = 'v1'): void
    {
        $prefix = Config::get('lunar-api.route_prefix');

        $this->baseUri = "/{$prefix}/{$path}";
    }

    /**
     * Bootstrap the server when it is handling an HTTP request.
     *
     * @return void
     */
    public function serving(): void
    {
        // no-op
    }

    /**
     * Get the server's list of schemas.
     *
     * @return array
     */
    protected function allSchemas(): array
    {
        return [
            BrandSchema::class,
            CollectionSchema::class,
            CollectionGroupSchema::class,
            DefaultUrlSchema::class,
            ImageSchema::class,
            MediaSchema::class,
            PriceSchema::class,
            ProductAssociationSchema::class,
            ProductSchema::class,
            ProductVariantSchema::class,
            ThumbnailSchema::class,
            UrlSchema::class,
            ...$this->getAdditionalServerSchemas(),
        ];
    }

    /**
     * Determine if the server is authorizable.
     *
     * @return bool
     */
    public function authorizable(): bool
    {
        // TODO: Write policies
        return false;
    }
}
