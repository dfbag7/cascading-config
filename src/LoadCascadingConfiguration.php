<?php namespace Dimbo\CascadingConfig;

use Illuminate\Contracts\Foundation\Application;
use SplFileInfo as SysSplFileInfo;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class LoadCascadingConfiguration
{
    public function bootstrap(Application $app)
    {
        if($app->configurationIsCached())
        {
            return;
        }

        $envName = $app->environment();

        $envConfigPath = (new SysSplFileInfo(config_path("../config.$envName")))->getRealPath();
        if($envConfigPath === false)
        {
            return;
        }

        $config = $app->make('config');

        // Run through all PHP files in the current environment's config directory.
        // With each file, check if there is a current config key with the name.
        // If there's not, initialize it as an empty array.
        // Then, use array_replace_recursive() to merge the environment config values
        // into the base values.
        foreach(Finder::create()->files()->name('*.php')->in($envConfigPath) as $file)
        {
            $keyName = $this->getNestedDirectory($file, $envConfigPath) . basename($file->getRealPath(), '.php');

            $oldValues = $config->get($keyName) ?: [];
            $newValues = require $file->getRealPath();

            // Replace any matching values in the old config with the new ones.
            $config->set($keyName, array_replace_recursive($oldValues, $newValues));
        }
    }

    /**
     * Get the configuration file nesting path.
     * Copied from \Illuminate\Foundation\Bootstrap\LoadConfiguration class.
     *
     * @param SysSplFileInfo $file
     * @param string $configPath
     *
     * @return string
     */
    protected function getNestedDirectory(SysSplFileInfo $file, string $configPath): string
    {
        $directory = $file->getPath();

        if($nested = trim(str_replace($configPath, '', $directory), DIRECTORY_SEPARATOR))
        {
            $nested = str_replace(DIRECTORY_SEPARATOR, '.', $nested) . '.';
        }

        return $nested;
    }
}
