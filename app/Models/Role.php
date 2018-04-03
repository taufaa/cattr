<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Role
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @property User[] $users
 * @property Rule[] $rules
 */
class Role extends Model
{
    use SoftDeletes;

    /**
     * table name from database
     * @var string
     */
    protected $table = 'role';

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id');
    }

    /**
     * @return HasMany
     */
    public function rules(): HasMany
    {
        return $this->hasMany(Rule::class, 'role_id');
    }

    /**
     * @throws \Exception
     */
    public static function updateRules(): void
    {
        /** @var array[] $actionList */
        $actionList = Rule::getActionList();

        /** @var Role $role */
        foreach (static::where([])->get() as $role) {
            foreach ($role->rules as $rule) {
                if (isset($actionList[$rule->object][$rule->action])) {
                    continue;
                }

                $rule->delete();
            }

            foreach ($actionList as $object => $actions) {
                foreach ($actions as $action => $name) {
                    $rule = Rule::where([
                        'role_id' => $role->id,
                        'object' => $object,
                        'action' => $action,
                    ])->first();

                    if (!$rule) {
                        Rule::create([
                            'role_id' => $role->id,
                            'object' => $object,
                            'action' => $action,
                            'allow' => false,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * @param $user
     * @param $object
     * @param $action
     * @return bool
     */
    public static function can($user, $object, $action): bool
    {
        $rule = Rule::where([
            'role_id' => $user->role->id,
            'object' => $object,
            'action' => $action,
        ])->first();

        if (!$rule) {
            return false;
        }

        return (bool) $rule->allow;
    }

}