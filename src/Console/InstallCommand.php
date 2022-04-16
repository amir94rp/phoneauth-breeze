<?php

namespace PhoneAuth\Breeze\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phoneauth:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the PhoneAuth controllers and resources';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \Symfony\Component\Process\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Process\Exception\ProcessSignaledException
     * @throws \Symfony\Component\Process\Exception\ProcessTimedOutException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    public function handle()
    {
        // Install Inertia...
        $this->requireComposerPackages(
            'inertiajs/inertia-laravel:^0.4.3', 'laravel/sanctum:^2.6', 'phoneauth/support:^1.0.1',
            'doctrine/dbal:^3.2', 'tightenco/ziggy:^1.0');

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                    '@inertiajs/inertia' => '^0.10.0', '@inertiajs/inertia-vue3' => '^0.5.1', '@inertiajs/progress' => '^0.2.6',
                    'tailwindcss' => '^2.1.2','@tailwindcss/forms' => '^0.2.1', "tailwindcss-dir"   => "^4.0.0",
                    '@vue/compiler-sfc' => '^3.0.5', 'autoprefixer' => '^10.2.4', 'postcss' => '^8.2.13', 'postcss-import' => '^14.0.1',
                    'vue' => '^3.0.5', 'vue-loader' => '^16.1.2'] + $packages;
        });

        // Controllers...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers/Auth'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../publish/app/Http/Controllers/Auth',
            app_path('Http/Controllers/Auth'));

        // Requests...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Requests/Auth'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../publish/app/Http/Requests/Auth',
            app_path('Http/Requests/Auth'));

        //notifications...
        (new Filesystem)->ensureDirectoryExists(app_path('Notifications'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../publish/app/Notifications',
            app_path('Notifications'));

        //models...
        copy(__DIR__.'/../../publish/app/Models/User.php',
            app_path('Models/User.php'));

        // Middleware...
        $this->installMiddlewareAfter('SubstituteBindings::class',
            '\App\Http\Middleware\HandleInertiaRequests::class');

        $this->addRouteMiddleware('EnsureEmailIsVerified::class','phoneauth' ,
            "\PhoneAuth\Support\Middleware\EnsureNumberIsVerified::class");

        copy(__DIR__.'/../../publish/app/Http/Middleware/HandleInertiaRequests.php',
            app_path('Http/Middleware/HandleInertiaRequests.php'));

        // Views...
        copy(__DIR__.'/../../publish/resources/Views/app.blade.php',
            resource_path('views/app.blade.php'));

        // Components + Pages...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));

        (new Filesystem)->copyDirectory(__DIR__.'/../../publish/resources/js/Components',
            resource_path('js/Components'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../publish/resources/js/Layouts',
            resource_path('js/Layouts'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../publish/resources/js/Pages',
            resource_path('js/Pages'));

        // Routes...
        copy(__DIR__.'/../../publish/routes/web.php', base_path('routes/web.php'));
        copy(__DIR__.'/../../publish/routes/auth.php', base_path('routes/auth.php'));

        // "Dashboard" Route...
        $this->replaceInFile('/home', '/dashboard', resource_path('js/Pages/Welcome.vue'));
        $this->replaceInFile('Home', 'Dashboard', resource_path('js/Pages/Welcome.vue'));
        $this->replaceInFile('/home', '/dashboard', app_path('Providers/RouteServiceProvider.php'));

        // Tailwind / Webpack...
        copy(__DIR__.'/../../publish/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__.'/../../publish/webpack.mix.js', base_path('webpack.mix.js'));
        copy(__DIR__.'/../../publish/webpack.config.js', base_path('webpack.config.js'));
        copy(__DIR__.'/../../publish/jsconfig.json', base_path('jsconfig.json'));

        //resource
        copy(__DIR__.'/../../publish/resources/css/app.css', resource_path('css/app.css'));
        copy(__DIR__.'/../../publish/resources/js/app.js', resource_path('js/app.js'));

        //migrations
        copy(__DIR__.'/../../publish/migrations/2021_11_20_120336_modify_password_resets_table.php',
            database_path('migrations/2021_11_20_120336_modify_password_resets_table.php'));
        copy(__DIR__.'/../../publish/migrations/2021_11_20_115727_modify_users_table.php',
            database_path('migrations/2021_11_20_115727_modify_users_table.php'));

        //config file
        copy(__DIR__.'/../../publish/config/phoneauth.php', config_path('phoneauth.php'));

        //language files
        copy(__DIR__.'/../../publish/resources/lang/en/phoneauth.php' ,
            resource_path('lang/en/phoneauth.php'));

        $this->info('phoneauth scaffolding installed successfully.');
        $this->comment('Please execute the "npm install && npm run dev" command to build your assets.');
    }

    /**
     * Install the middleware to a group in the application Http Kernel.
     *
     * @param  string  $after
     * @param  string  $name
     * @param  string  $group
     * @return void
     */
    protected function installMiddlewareAfter($after, $name, $group = 'web')
    {
        $httpKernel = file_get_contents(app_path('Http/Kernel.php'));

        $middlewareGroups = Str::before(Str::after($httpKernel, '$middlewareGroups = ['), '];');
        $middlewareGroup = Str::before(Str::after($middlewareGroups, "'$group' => ["), '],');

        if (! Str::contains($middlewareGroup, $name)) {
            $modifiedMiddlewareGroup = str_replace(
                $after.',',
                $after.','.PHP_EOL.'            '.$name.',',
                $middlewareGroup,
            );

            file_put_contents(app_path('Http/Kernel.php'), str_replace(
                $middlewareGroups,
                str_replace($middlewareGroup, $modifiedMiddlewareGroup, $middlewareGroups),
                $httpKernel
            ));
        }
    }

    /**
     * add middleware to $routeMiddleware.
     *
     * @param string $after
     * @param string $name
     * @param $middleware
     * @return void
     */

    protected function addRouteMiddleware($after, $name, $middleware)
    {
        $httpKernel = file_get_contents(app_path('Http/Kernel.php'));

        $middlewareGroups = Str::before(Str::after($httpKernel, '$routeMiddleware = ['), '];');

        if (! Str::contains($middlewareGroups, $name)) {
            $modifiedMiddlewareGroup = str_replace(
                $after.',',
                $after.','.PHP_EOL.'        \'' . $name . '\' => '. $middleware . ',',
                $middlewareGroups,
            );

            file_put_contents(app_path('Http/Kernel.php'), str_replace(
                $middlewareGroups,
                $modifiedMiddlewareGroup,
                $httpKernel
            ));
        }
    }

    /**
     * Installs the given Composer Packages into the application.
     *
     * @param mixed $packages
     * @return void
     * @throws \Symfony\Component\Process\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Process\Exception\ProcessSignaledException
     * @throws \Symfony\Component\Process\Exception\ProcessTimedOutException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    protected function requireComposerPackages($packages)
    {
        $command = array_merge(
            $command ?? ['composer', 'require'],
            is_array($packages) ? $packages : func_get_args()
        );

        (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }

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
     * Delete the "node_modules" directory and remove the associated lock files.
     *
     * @return void
     */
    protected static function flushNodeModules()
    {
        tap(new Filesystem, function ($files) {
            $files->deleteDirectory(base_path('node_modules'));

            $files->delete(base_path('yarn.lock'));
            $files->delete(base_path('package-lock.json'));
        });
    }

    /**
     * Replace a given string within a given file.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $path
     * @return void
     */
    protected function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }
}
