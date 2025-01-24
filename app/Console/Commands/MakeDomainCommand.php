<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeDomainCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:domain';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new domain';

    protected array $directories = [
        'Collections',
        'Actions',
        'Providers',
        'QueryBuilders'
    ];

    protected string $domain = '';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $this->domain = ucfirst($this->ask('What is the domain name?'));

            File::makeDirectory(base_path("src/Domain/$this->domain"));

            foreach ($this->directories as $dir) {
                File::makeDirectory(base_path("src/Domain/$this->domain/$dir"));
            }

            $this->registerServiceProvider();

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());

            return self::FAILURE;
        }
    }

    protected function registerServiceProvider(): void
    {
        $this->makeDomainServiceProvider($this->domain, 'service_provider');
        $this->makeDomainServiceProvider('Actions', 'actions_service_provider');

        $domainProviderPath = app_path('Providers/DomainServiceProvider.php');

        $domainProvider = file_get_contents($domainProviderPath);

        if (Str::contains(
            $domainProvider,
            'Domain\\' . $this->domain . '\\Providers\\' . $this->domain . 'ServiceProvider'
        )) {
            return;
        }

        file_put_contents(
            $domainProviderPath,
            preg_replace(
                '/public function register\(\): void\n+\s+\{\n+/m',
                $this->registerMethodSignature() . $this->appRegister($this->domain),
                $domainProvider
            )
        );
    }

    protected function makeDomainServiceProvider(string $name, string $stub): void
    {
        file_put_contents(
            base_path("src/Domain/$this->domain/Providers/{$name}ServiceProvider.php"),
            str_replace(
                "{{Domain}}",
                $this->domain,
                file_get_contents(base_path("stubs/domain/$stub.stub"))
            )
        );
    }

    protected function registerMethodSignature(): string
    {
        return 'public function register(): void' . PHP_EOL . '    {' . PHP_EOL;
    }

    protected function appRegister(string $prefix): string
    {
        return $this->tab(2) . '$this->app->register(' . PHP_EOL .
            $this->tab(3) . "\\Domain\\$prefix\\Providers\\{$prefix}ServiceProvider::class" . PHP_EOL .
            $this->tab(2) . ');' . PHP_EOL . PHP_EOL;
    }

    protected function tab(int $tabCount = 1): string
    {
        return str_repeat(' ', $tabCount * 4);
    }
}
