<?php

namespace Botble\Base\Supports;

use ArrayAccess;
use Botble\Base\Events\SendMailEvent;
use Botble\Base\Jobs\SendMailJob;
use Botble\Setting\Supports\SettingStore;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\URL;
use RvMedia;
use Swift_TransportException;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Throwable;

class EmailHandler
{
    /**
     * @var string
     */
    protected $type = 'plugins';

    /**
     * @var string
     */
    protected $module = null;

    /**
     * @var string
     */
    protected $template = null;

    /**
     * @var array
     */
    protected $templates = [];

    /**
     * @var array
     */
    protected $variableValues = [];

    /**
     * @param string $module
     * @return $this
     */
    public function setModule(string $module): self
    {
        $this->module = $module;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return EmailHandler
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * @param string|null $template
     * @return EmailHandler
     */
    public function setTemplate(?string $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return array
     */
    public function getCoreVariables(): array
    {
        return [
            'header' => trans('core/base::base.email_template.header'),
            'footer' => trans('core/base::base.email_template.footer'),
            'site_title' => trans('core/base::base.email_template.site_title'),
            'site_url' => trans('core/base::base.email_template.site_url'),
            'site_logo' => trans('core/base::base.email_template.site_logo'),
            'date_time' => trans('core/base::base.email_template.date_time'),
            'date_year' => trans('core/base::base.email_template.date_year'),
            'site_admin_email' => trans('core/base::base.email_template.site_admin_email'),
        ];
    }

    /**
     * @param string $variable
     * @param string $value
     * @param string|null $module
     * @return $this
     */
    public function setVariableValue(string $variable, string $value, string $module = null): self
    {
        Arr::set($this->variableValues, ($module ?: $this->module) . '.' . $variable, $value);

        return $this;
    }

    /**
     * @param string|null $module
     * @return array
     */
    public function getVariableValues(?string $module = null): array
    {
        if ($module) {
            return Arr::get($this->variableValues, $module, []);
        }

        return $this->variableValues;
    }

    /**
     * @param array $data
     * @param string|null $module
     * @return $this
     */
    public function setVariableValues(array $data, ?string $module = null): self
    {
        foreach ($data as $name => $value) {
            $this->variableValues[$module ?: $this->module][$name] = $value;
        }

        return $this;
    }

    /**
     * @param string $module
     * @param array $data
     * @param string $type
     * @return $this
     */
    public function addTemplateSettings(string $module, array $data, string $type = 'plugins'): self
    {
        if (empty($data)) {
            return $this;
        }

        $this->module = $module;

        Arr::set($this->templates, $type . '.' . $module, $data);

        foreach ($data['templates'] as $key => &$template) {
            if (!isset($template['variables'])) {
                $this->templates[$type][$module]['templates'][$key]['variables'] = Arr::get($data, 'variables', []);
            }

            $this->templates[$type][$module]['templates'][$key]['path'] = platform_path($type . '/' . $module . '/resources/email-templates/' . $key . '.tpl');
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getTemplates(): array
    {
        return $this->templates;
    }

    /**
     * @param string $type
     * @param string $module
     * @param string $name
     * @return array|ArrayAccess|mixed
     */
    public function getTemplateData(string $type, string $module, string $name)
    {
        return Arr::get($this->templates, $type . '.' . $module . '.templates.' . $name);
    }

    /**
     * @param string $type
     * @param string $module
     * @param string $name
     * @return array|ArrayAccess|mixed
     */
    public function getVariables(string $type, string $module, string $name)
    {
        $this->template = $name;

        return $this->getCoreVariables() + Arr::get($this->getTemplateData($type, $module, $name), 'variables', []);
    }

    /**
     * @param string $template
     * @param string|null|array $email
     * @param array $args
     * @param bool $debug
     * @param string $type
     * @param null $subject
     * @return bool
     * @throws FileNotFoundException
     * @throws Throwable
     */
    public function sendUsingTemplate(string $template, $email = null, array $args = [], bool $debug = false, string $type = 'plugins', $subject = null): bool
    {
        if (!$this->templateEnabled($template)) {
            return false;
        }

        $this->type = $type;
        $this->template = $template;

        if (!$subject) {
            $subject = $this->getSubject();
        }

        $this->send($this->getContent(), $subject, $email, $args, $debug);

        return true;
    }

    /**
     * @param string $template
     * @param string $type
     * @return array|SettingStore|string|null
     */
    public function templateEnabled(string $template, string $type = 'plugins')
    {
        return get_setting_email_status($type, $this->module, $template);
    }

    /**
     * @param string $content
     * @param string $title
     * @param string|array $to
     * @param array $args
     * @param bool $debug
     * @throws Throwable
     */
    public function send(string $content, string $title, $to = null, array $args = [], bool $debug = false)
    {
        try {
            if (empty($to)) {
                $to = get_admin_email()->toArray();
                if (empty($to)) {
                    $to = setting('email_from_address', config('mail.from.address'));
                }
            }

            $content = $this->prepareData($content);
            $title = $this->prepareData($title);

            if (setting('using_queue_to_send_mail', config('core.base.general.send_mail_using_job_queue'))) {
                dispatch(new SendMailJob($content, $title, $to, $args, $debug));
            } else {
                event(new SendMailEvent($content, $title, $to, $args, $debug));
            }
        } catch (Exception $exception) {
            if ($debug) {
                if ($exception instanceof Swift_TransportException && $exception->getPrevious()) {
                    throw $exception->getPrevious();
                }

                throw $exception;
            }

            info($exception->getMessage());
            $this->sendErrorException($exception);
        }
    }

    /**
     * @param string $content
     * @return string
     */
    public function prepareData(string $content): string
    {
        $this->initVariableValues();

        if (!empty($content)) {
            $content = $this->replaceVariableValue(array_keys($this->getCoreVariables()), 'core', $content);

            if ($this->module && $this->template) {
                $variables = $this->getVariables($this->type ?: 'plugins', $this->module, $this->template);

                $content = $this->replaceVariableValue(
                    array_keys($variables),
                    $this->module,
                    $content
                );
            }
        }

        return apply_filters(BASE_FILTER_EMAIL_TEMPLATE, $content);
    }

    public function initVariableValues()
    {
        $this->variableValues['core'] = [
            'header' => apply_filters(
                BASE_FILTER_EMAIL_TEMPLATE_HEADER,
                get_setting_email_template_content('core', 'base', 'header')
            ),
            'footer' => apply_filters(
                BASE_FILTER_EMAIL_TEMPLATE_FOOTER,
                get_setting_email_template_content('core', 'base', 'footer')
            ),
            'site_title' => setting('admin_title') ?: config('app.name'),
            'site_url' => url(''),
            'site_logo' => setting('admin_logo') ? RvMedia::getImageUrl(setting('admin_logo')) : url(config('core.base.general.logo')),
            'date_time' => Carbon::now()->toDateTimeString(),
            'date_year' => Carbon::now()->format('Y'),
            'site_admin_email' => get_admin_email()->first(),
        ];
    }

    /**
     * @param array $variables
     * @param string $module
     * @param string $content
     * @return string
     */
    protected function replaceVariableValue(array $variables, string $module, string $content): string
    {
        do_action('email_variable_value');

        foreach ($variables as $variable) {
            $keys = [
                '{{ ' . $variable . ' }}',
                '{{' . $variable . '}}',
                '{{ ' . $variable . '}}',
                '{{' . $variable . ' }}',
                '<?php echo e(' . $variable . '); ?>',
            ];

            foreach ($keys as $key) {
                $content = str_replace($key, $this->getVariableValue($variable, $module), $content);
            }
        }

        return $content;
    }

    /**
     * @param string $variable
     * @param string $module
     * @param string $default
     * @return string
     */
    public function getVariableValue(string $variable, string $module, string $default = ''): string
    {
        return (string)Arr::get($this->variableValues, $module . '.' . $variable, $default);
    }

    /**
     * Sends an email to the developer about the exception.
     *
     * @param Exception|Throwable $exception
     * @return void
     *
     * @throws Throwable
     */
    public function sendErrorException(Exception $exception)
    {
        try {
            $ex = FlattenException::create($exception);

            $url = URL::full();
            $error = $this->renderException($exception);

            $this->send(
                view('core/base::emails.error-reporting', compact('url', 'ex', 'error'))->render(),
                $exception->getFile(),
                !empty(config('core.base.general.error_reporting.to')) ?
                    config('core.base.general.error_reporting.to') :
                    get_admin_email()->toArray()
            );
        } catch (Exception $ex) {
            info($ex->getMessage());
        }
    }

    /**
     * @param Throwable|Exception $exception
     * @return string
     */
    protected function renderException($exception): string
    {
        $renderer = new HtmlErrorRenderer(true);

        $exception = $renderer->render($exception);

        if (!headers_sent()) {
            http_response_code($exception->getStatusCode());

            foreach ($exception->getHeaders() as $name => $value) {
                header($name . ': ' . $value, false);
            }
        }

        return $exception->getAsString();
    }

    /**
     * @param string $template
     * @param string $type
     * @return string|null
     */
    public function getTemplateContent(string $template, string $type = 'plugins'): ?string
    {
        $this->template = $template;
        $this->type = $type;

        return get_setting_email_template_content($type, $this->module, $template);
    }

    /**
     * @param string $template
     * @param string $type
     * @return array|SettingStore|string|null
     */
    public function getTemplateSubject(string $template, string $type = 'plugins')
    {
        return setting(
            get_setting_email_subject_key($type, $this->module, $template),
            trans(config(
                $type . '.' . $this->module . '.email.templates.' . $template . '.subject',
                ''
            ))
        );
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->prepareData(get_setting_email_template_content($this->type, $this->module, $this->template));
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->prepareData($this->getTemplateSubject($this->template, $this->type));
    }
}
