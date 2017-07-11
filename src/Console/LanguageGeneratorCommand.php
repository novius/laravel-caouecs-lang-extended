<?php

namespace Novius\Caouecs\Lang\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LanguageGeneratorCommand extends Command
{
    protected $signature = 'lang:install {local : The local of language to install (ex: lang:install fr)}
                            {--force : Erase existing files.}';

    protected $description = 'Install Laravel default languages files for selected language';

    protected static $requiredVendorLanguagesPath = 'vendor/caouecs/laravel-lang/src';

    protected function getExcludedFilesFromConfig()
    {
        return config('lang-generator.file_exceptions', []);
    }

    public function handle()
    {
        // Make an array with excluded files' name
        $excludedFiles = $this->getExcludedFilesFromConfig();
        $local = (string) $this->argument('local');
        $forceEraseExistingFiles = $this->option('force');
        $targetPath = base_path('resources/lang');
        $localFilesPath = base_path(static::$requiredVendorLanguagesPath).DIRECTORY_SEPARATOR.$local.DIRECTORY_SEPARATOR;

        if (! is_dir($targetPath) || ! is_writable($targetPath)) {
            return $this->error('The lang path "resources/lang/" does not exist or not writable.');
        }

        if (! is_dir($localFilesPath)) {
            return $this->error('The language wanted doesn\'t exists.');
        }

        $targetPath .= DIRECTORY_SEPARATOR.$local;
        if (! is_dir($targetPath)) {
            if (! File::makeDirectory($targetPath)) {
                return $this->error('Unable to create lang folder in : resources/lang');
            }
        }

        $files = scandir($localFilesPath);
        if ($files === false) {
            return $this->error('Failure on files listing.');
        }

        // Remove files exceptions
        $files = $this->filterExcludedFiles($localFilesPath, $files, $excludedFiles);

        if (! $forceEraseExistingFiles) {
            // No force option : keep only non-existing files
            $files = array_filter($files, function ($filename) use ($targetPath) {
                return ! file_exists($targetPath.DIRECTORY_SEPARATOR.$filename);
            });
        }

        if (empty($files)) {
            return $this->error('Nothing to do : no file.');
        }

        $files = $this->makeSourcePathFromFilename($localFilesPath, $files);

        $this->line('Language installation ...');
        $this->line('Copying '.count($files).' files ...');

        foreach ($files as $filePath) {
            $fileName = pathinfo($filePath, PATHINFO_BASENAME);
            $targetFilePath = $targetPath.DIRECTORY_SEPARATOR.$fileName;
            if (! $forceEraseExistingFiles && File::exists($targetFilePath)) {
                continue;
            }
            File::copy($filePath, $targetFilePath);
        }

        return $this->info(mb_strtoupper($local).' language successfully installed.');
    }

    /**
     * @param $localFilesPath : the path of source language directory
     * @param array $files : the array of filename
     * @return array : the array of filename without files present in exceptions config
     */
    protected function filterExcludedFiles($localFilesPath, array $files, array $excludedFiles)
    {
        $files = array_filter($files, function ($file) use ($localFilesPath, $excludedFiles) {
            return is_file($localFilesPath.DIRECTORY_SEPARATOR.$file) && ! in_array($file, $excludedFiles);
        });

        return $files;
    }

    /**
     * @param $localFilesPath : the path of source language directory
     * @param array $files : the array of filename
     * @return array : an array of source files path
     */
    protected function makeSourcePathFromFilename($localFilesPath, array $files)
    {
        // Make source path from filename
        array_walk($files, function (&$value) use ($localFilesPath) {
            $value = $localFilesPath.$value;
        });

        return $files;
    }
}
