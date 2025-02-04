<?php

namespace Nyumbapoa\Pesaswap\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallPesaswapPackage extends Command
{
    protected $signature = 'pesaswap:install';

    protected $description = 'Install the Pesaswap Package';

    public function handle()
    {
        $this->info('Installing Pesaswap Package...');

        $this->info('Publishing configuration...');

        if (! $this->configExists('mpesa.php')) {
            $this->publishConfiguration();
            $this->info('Published configuration');
        } else {
            if ($this->shouldOverwriteConfig()) {
                $this->info('Overwriting configuration file...');
                $this->publishConfiguration(true);
            } else {
                $this->info('Existing configuration was not overwritten');
            }
        }

        $this->info('Installed Pesaswap Package');
    }

    public function configExists($fileName)
    {
        return File::exists(config_path($fileName));
    }

    public function shouldOverwriteConfig()
    {
        return $this->confirm(
            'Config file already exists. Do you want to overwrite it?',
            false
        );
    }

    public function publishConfiguration($forcePublish = false)
    {
        $params = [
            '--provider' => "Nyumbapoa\Pesaswap\PesaswapServiceProvider",
            '--tag' => 'pesaswap-config',
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
    }
}