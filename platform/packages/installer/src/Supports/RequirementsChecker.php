<?php

namespace Botble\Installer\Supports;

use Illuminate\Support\Facades\File;

class RequirementsChecker
{
    /**
     * Minimum PHP Version Supported (Override is in installer.php config file).
     *
     * @var string
     */
    protected $phpVersion = null;

    /**
     * Check for the server requirements.
     *
     * @param array $requirements
     * @return array
     */
    public function check(array $requirements): array
    {
        $results = [];

        foreach ($requirements as $type => $item) {
            switch ($type) {
                // check php requirements
                case 'php':
                    foreach ($item as $requirement) {
                        $results['requirements'][$type][$requirement] = true;

                        if (!extension_loaded($requirement)) {
                            $results['requirements'][$type][$requirement] = false;

                            $results['errors'] = true;
                        }
                    }

                    break;

                case 'apache':
                    foreach ($item as $requirement) {
                        // if function doesn't exist we can't check apache modules
                        if (function_exists('apache_get_modules')) {
                            $results['requirements'][$type][$requirement] = true;

                            if (!in_array($requirement, apache_get_modules())) {
                                $results['requirements'][$type][$requirement] = false;

                                $results['errors'] = true;
                            }
                        }
                    }

                    break;

                case 'permissions':
                    foreach ($item as $folder) {
                        $results['requirements'][$type][$folder] = File::isWritable(base_path($folder));
                    }

                    break;
            }
        }

        return $results;
    }

    /**
     * Check PHP version requirement.
     *
     * @param string|null $minPhpVersion
     * @return array
     */
    public function checkPhpVersion(string $minPhpVersion = null): array
    {
        $minVersionPhp = $minPhpVersion;
        $currentPhpVersion = $this->getPhpVersionInfo();
        $supported = false;

        if (version_compare($currentPhpVersion['version'], $minVersionPhp, '>=') >= 0) {
            $supported = true;
        }

        return [
            'full' => $currentPhpVersion['full'],
            'current' => $currentPhpVersion['version'],
            'minimum' => $minVersionPhp,
            'supported' => $supported,
        ];
    }

    /**
     * Get current Php version information
     *
     * @return array
     */
    protected static function getPhpVersionInfo(): array
    {
        $currentVersionFull = PHP_VERSION;
        preg_match('#^\\d+(\\.\\d+)*#', $currentVersionFull, $filtered);
        $currentVersion = $filtered[0];

        return [
            'full' => $currentVersionFull,
            'version' => $currentVersion,
        ];
    }
}
