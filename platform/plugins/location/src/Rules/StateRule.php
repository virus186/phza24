<?php

namespace Botble\Location\Rules;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Location\Repositories\Interfaces\StateInterface;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class StateRule implements DataAwareRule, Rule
{
    /**
     * All the data under validation.
     *
     * @var array
     */
    protected $data = [];

    /**
     *
     * @var string|null
     */
    protected $countryKey;

    /**
     * Create a new rule instance.
     *
     * @param string|null $countryKey
     */
    public function __construct(?string $countryKey = '')
    {
        $this->countryKey = $countryKey;
    }

    /**
     * Set the data under validation.
     *
     * @param array $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $condition = [
            'id' => $value,
            'status' => BaseStatusEnum::PUBLISHED,
        ];

        if ($this->countryKey) {
            $countryId = Arr::get($this->data, $this->countryKey);
            if (!$countryId) {
                return false;
            }
            $condition['country_id'] = $countryId;
        }

        return app(StateInterface::class)->count($condition);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.exists');
    }
}
