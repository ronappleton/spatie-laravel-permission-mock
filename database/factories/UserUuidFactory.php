<?php

declare(strict_types=1);

namespace Database\Factories;

use Appleton\SpatieLaravelPermissionMock\Models\User;
use Appleton\SpatieLaravelPermissionMock\Models\UserUuid;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserUuidFactory extends Factory
{
    protected $model = UserUuid::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make($this->faker->password),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
