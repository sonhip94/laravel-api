<?php

namespace App\Services\Api;

use App\Services\Api\Contracts\UserServiceInterface;
use App\Models\User;

class UserService extends BaseService implements UserServiceInterface
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $inputs Associative array [name, email, password]
     *
     * @return \App\Models\User|array
     */
    public function create(array $inputs)
    {
        return $this->model->create($inputs);
    }

    /**
     * @param \App\Models\User $user
     * @param array            $inputs Associative array [name]
     *
     * @return boolean
     */
    public function update($user, $inputs)
    {
        return $user->update([
            'name' => $inputs['name'],
        ]);
    }
}
