<?php
namespace App\Services\Api\Contracts;

interface UserServiceInterface extends BaseServiceInterface
{
    /**
     * @param array $inputs Associative array [name, email, password]
     *
     * @return \App\Models\User|array
     */
    public function create(array $inputs);

    /**
     * @param \App\Models\User $user
     * @param array $inputs Associative array [name, birthday, job, gender]
     *
     * @return boolean
     */
    public function update($user, $inputs);
}
