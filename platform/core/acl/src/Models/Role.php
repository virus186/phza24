<?php

namespace Botble\ACL\Models;

use Botble\ACL\Traits\PermissionTrait;
use Botble\Base\Models\BaseModel;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends BaseModel
{
    use PermissionTrait;

    /**
     * {@inheritDoc}
     */
    protected $table = 'roles';

    /**
     * {@inheritDoc}
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'name',
        'slug',
        'permissions',
        'description',
        'is_default',
        'created_by',
        'updated_by',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'permissions' => 'json',
    ];

    public function getPermissionsAttribute(?string $value): array
    {
        try {
            return json_decode((string)$value, true) ?: [];
        } catch (Exception $exception) {
            return [];
        }
    }

    public function setPermissionsAttribute(array $permissions)
    {
        $this->attributes['permissions'] = $permissions ? json_encode($permissions) : '';
    }

    /**
     * {@inheritDoc}
     */
    public function delete(): ?bool
    {
        if ($this->exists) {
            $this->users()->detach();
        }

        return parent::delete();
    }

    public function users(): BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'role_users', 'role_id', 'user_id')
            ->withTimestamps();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
}
