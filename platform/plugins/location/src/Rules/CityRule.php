<?php

namespace Botble\Location\Rules;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Location\Repositories\Interfaces\CityInterface;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class CityRule implements DataAwareRule, Rule
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
    protected $stateKey;

    /**
     * Create a new rule instance.
     *
     * @param string|null $stateKey
     */
    public function __construct(?string $stateKey = '')
    {
        $this->stateKey = $stateKey;
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

        if ($this->stateKey) {
            $stateId = Arr::get($this->data, $this->stateKey);
            if (!$stateId) {
                return false;
            }
            $condition['state_id'] = $stateId;
        }

        return app(CityInterface::class)->count($condition);
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
