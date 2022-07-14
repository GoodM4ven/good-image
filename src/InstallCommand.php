<?php

namespace GoodM4ven\GoodImage;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'good-image:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register the Good Image front-end packages.';

    /**
     * Update the "package.json" file.
     *
     * @param  callable  $callback
     * @param  bool  $dev
     * @return void
     */
    protected static function updateNodePackages(callable $callback, $dev = true)
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->updateNodePackages(function ($packages) {
            return [
                'tailwindcss' => '^3.1.5',
                'autoprefixer' => '^10.4.7',
                'postcss' => '^8.4.14',
                'alpinejs' => '^3.10.2',
                "@alpinejs/intersect" => "^3.10.2",
                'blurhash' => '^1.1.5',
            ] + $packages;
        });

        $this->callSilently('vendor:publish', [
            '--provider' => 'GoodM4ven\GoodImage\GoodImageServiceProvider',
        ]);

        $this->info('Front-end packages are ready to be installed...');
        $this->info('The configuration, component view, and assets are extracted...');

        $this->newLine();
        $this->comment('Now do NOT miss the next step in README. I\'m watching you! 👀');

        return static::SUCCESS;
    }
}
