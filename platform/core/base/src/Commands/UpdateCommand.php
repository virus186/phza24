<?php

namespace Botble\Base\Commands;

use BaseHelper;
use Botble\Base\Supports\Core;
use DOMDocument;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class UpdateCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cms:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update CMS';

    /**
     * Execute the console command.
     * @throws \Throwable
     */
    public function handle()
    {
        if (!config('core.base.general.enable_system_updater')) {
            $this->error('Please enable system updater');

            return self::FAILURE;
        }

        if (!version_compare(phpversion(), '8.0.2', '>=') > 0 && !config('core.base.general.upgrade_php_require_disabled')) {
            $this->error(trans('core/base::system.upgrade_php_version_required', ['version' => phpversion()]));

            return self::FAILURE;
        }

        $this->info('Checking for the latest version...');

        BaseHelper::maximumExecutionTimeAndMemoryLimit();

        $api = new Core();
        $updateData = $api->checkUpdate();

        $verifyLicense = $api->verifyLicense();

        if (!$verifyLicense['status']) {
            $this->error('Your license is invalid. Please activate your license first!');

            return self::FAILURE;
        }

        $this->newLine();

        if ($updateData['status']) {
            $updateData['message'] = 'A new version (' . $updateData['version'] . ' / released on ' . $updateData['release_date'] . ') is available to update!';

            $this->linePrimary($updateData['message']);

            $this->newLine();

            $array = [
                'Please backup your database and script files before upgrading',
                'You need to activate your license before doing upgrade.',
                'If you don\'t need this 1-click update, you can disable it in .env by adding CMS_ENABLE_SYSTEM_UPDATER=false',
                'It will override all files in platform/core, platform/packages, all plugins developed by us in platform/plugins and theme developed by us in platform/themes.',
            ];

            $this->alertBox($array, 'Please read before update', 'lineDanger');
            $this->newLine();

            $changelogs = $this->htmlToObj($updateData['changelog']);
            $this->alertBox($changelogs, 'Changelog', 'lineWarning');
            $this->newLine();

            if ($this->confirmToProceed('Do you want download & install Update CMS?', true)) {
                ob_start();
                $api->downloadUpdate($updateData['update_id'], $updateData['version']);
                $this->newLine();

                if ($this->confirmToProceed('Do you want run composer?', true)) {
                    $composer = $this->choice(
                        'Do you want run:',
                        ['install', 'update']
                    );

                    $process = new Process(['composer', $composer]);
                    $process->start();
                    $process->wait(function ($type, $buffer) {
                        $this->line($buffer);
                    });

                    $this->info('Updated successfully!');

                    return self::SUCCESS;
                }
            } else {
                return self::SUCCESS;
            }
        }

        $this->info('The system is up-to-date. There are no new versions to update!');

        return self::SUCCESS;
    }

    protected function htmlToObj($html): array
    {
        $dom = new DOMDocument();
        $dom->loadHTML($html);

        return Arr::flatten($this->elementToObj($dom->documentElement));
    }

    protected function elementToObj($element): array
    {
        $obj = [];
        foreach ($element->attributes as $attribute) {
            $obj[] = $attribute->value;
        }

        foreach ($element->childNodes as $subElement) {
            if ($subElement->nodeType == XML_TEXT_NODE) {
                $obj[] = $subElement->wholeText;
            } else {
                $obj[] = $this->elementToObj($subElement);
            }
        }

        return $obj;
    }

    public function alertBox(array $array, string $title, string $type = 'linePrimary')
    {
        $lengths = array_map('strlen', $array);
        $longestString = $array[array_search(max($lengths), $lengths)];

        $length = Str::length($longestString) + 14;
        $stringOpen = Str::padBoth('*', $length, ' *');
        $stringSpace = Str::padBoth(Str::padRight('', $length - 6), $length, ' * ');
        $stringTitle = Str::padBoth(Str::padRight($title, $length - 6), $length, ' * ');

        /**** Open ****/
        $this->$type($stringOpen);
        /**** Title ****/
        $this->$type($stringTitle, 'bold');
        /**** Close ****/
        $this->$type($stringOpen);

        foreach ($array as $a) {
            /**** Content ****/
            $stringContent = Str::padBoth(Str::padRight($a, $length - 6), $length, ' * ');
            $this->$type($stringContent);
        }

        /**** Close ****/
        $this->$type($stringOpen);
    }

    public function linePrimary(string $message, string $options = 'blink')
    {
        $this->lineBase($message, 'blue', $options);
    }

    public function lineWarning(string $message, string $options = 'blink')
    {
        $this->lineBase($message, 'yellow', $options);
    }

    public function lineDanger(string $message, string $options = 'blink')
    {
        $this->lineBase($message, 'red', $options);
    }

    public function lineBase(string $text, string $color = 'yellow', string $options = 'blink')
    {
        $this->line('<options=' . $options . ';fg=' . $color . '>' . $text . '</>');
    }
}
