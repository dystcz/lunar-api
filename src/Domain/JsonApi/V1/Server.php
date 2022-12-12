<?php

namespace Dystcz\LunarApi\Domain\JsonApi\V1;

use Dystcz\LunarApi\Domain\Brands\JsonApi\V1\BrandSchema;
use Dystcz\LunarApi\Domain\Collections\JsonApi\V1\CollectionSchema;
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
use LaravelJsonApi\Core\Server\Server as BaseServer;
use LaravelJsonApi\Core\Support\AppResolver;

class Server extends BaseServer
{
    /**
     * Server constructor.
     *
     * @param AppResolver $app
     * @param string $name
     */
    public function __construct(AppResolver $app, string $name)
    {
        $this->baseUri = '/' . Config::get('lunar-api.route_prefix') . '/v1';

        parent::__construct($app, $name);
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
            DefaultUrlSchema::class,
            ImageSchema::class,
            MediaSchema::class,
            PriceSchema::class,
            ProductAssociationSchema::class,
            ProductSchema::class,
            ProductVariantSchema::class,
            ThumbnailSchema::class,
            UrlSchema::class,
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
