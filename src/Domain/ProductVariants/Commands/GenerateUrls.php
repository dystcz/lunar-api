<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Lunar\Models\ProductVariant;

class GenerateUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lunar-api:variants:generate-urls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate urls for product variants.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Generating urls for variants...');

        $this->withProgressBar(
            ProductVariant::query()->cursor(),
            function ($variant) {
                if ($generator = Config::get('lunar.urls.generator')) {
                    app($generator)->handle($variant);
                }
            }
        );

        $this->info('Urls generated successfully.');
    }
}
