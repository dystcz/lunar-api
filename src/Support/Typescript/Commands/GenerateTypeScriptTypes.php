<?php

namespace Dystcz\LunarApi\Support\Typescript\Commands;

use Dystcz\LunarApi\Facades\LunarApi;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Spatie\TypeScriptTransformer\Formatters\PrettierFormatter;
use Spatie\TypeScriptTransformer\Structures\TransformedType;
use Spatie\TypeScriptTransformer\TypeScriptTransformer;
use Spatie\TypeScriptTransformer\TypeScriptTransformerConfig;

class GenerateTypeScriptTypes extends Command
{
    use ConfirmableTrait;

    protected TypeScriptTransformerConfig $config;

    protected TypeScriptTransformer $transformer;

    public function __construct()
    {
        parent::__construct();

        /** @var TypeScriptTransformerConfig $config */
        $this->config = App::make('lunar-api.typescript-transformer-config');

        // WARNING: For testing only, remove!
        $this->config->outputFile(LunarApi::getPackageRoot().'/resources/types/lunar-api-resources.d.ts');

        $this->transformer = TypeScriptTransformer::create($this->config);
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lunar-api:generate-typescript-types
                            {--force : Force the operation to run when in production}
                            {--path= : Specify a path with classes to transform}
                            {--output= : Use another file to output}
                            {--format : Use Prettier to format the output}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate typescript types for the Laravel API.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->confirmToProceed();

        if ($inputPath = $this->resolveInputPath()) {
            $this->config->autoDiscoverTypes($inputPath);
        }

        if ($outputFile = $this->resolveOutputFile()) {
            $this->config->outputFile($outputFile);
        }

        $collection = $this->transformer->transform();

        if ($this->option('format')) {
            $this->config->formatter(PrettierFormatter::class);
        }

        $this->table(
            ['PHP class', 'TypeScript entity'],
            Collection::make($collection)->map(fn (TransformedType $type, string $class) => [
                $class, $type->getTypeScriptName(),
            ])
        );

        $this->info("Generated {$collection->count()} types in \"{$this->config->getOutputFile()}\".");

        return self::SUCCESS;
    }

    /**
     * Resolve the output file.
     */
    private function resolveOutputFile(): ?string
    {
        $path = $this->option('output');

        if ($path === null) {
            return null;
        }

        return resource_path($path);
    }

    /**
     * Resolve the input path.
     */
    private function resolveInputPath(): ?string
    {
        $path = $this->option('path');

        if ($path === null) {
            return null;
        }

        if (file_exists($path)) {
            return $path;
        }

        return app_path($path);
    }
}
