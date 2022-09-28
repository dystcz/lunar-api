<?php

namespace Dystcz\LunarApi\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Yaml\Yaml;
use Vyuldashev\LaravelOpenApi\Generator;

class GenerateOpenApiSpec extends Command
{
    protected $signature = 'lunar-api:generate-open-api {collection=default}';

    protected $description = 'Generate OpenAPI specification';

    public function handle(Generator $generator): void
    {
        $collectionExists = collect(config('openapi.collections'))->has($this->argument('collection'));

        if (!$collectionExists) {
            $this->error('Collection "' . $this->argument('collection') . '" does not exist.');

            return;
        }

        $spec = $generator->generate($this->argument('collection'));

        // Print out the generated spec
        $this->line(
            $spec->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        if (Config::get('lunar-api.openapi.yaml_generate')) {
            // Store yaml file
            Storage::put(
                Config::get('lunar-api.openapi.folder_path') . '/' . Config::get('lunar-api.openapi.yaml_file_name'),
                Yaml::dump($spec->toArray())
            );
        }

        if (Config::get('lunar-api.openapi.json_generate')) {
            // Store json file
            Storage::put(
                Config::get('lunar-api.openapi.folder_path') . '/' . Config::get('lunar-api.openapi.json_file_name'),
                $spec->toJson()
            );
        }
    }
}
