<?php

namespace Tests;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    /**
     * @return void
     */
    public function testUserIsCreatedCorrectly(): void
    {
        $userData = [
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->unique()->safeEmail,
            'phone'      => $this->faker->unique()->phoneNumber,
            'password'   => Hash::make('123456'),
        ];

        $response = $this->post(
            env('APP_URL')."/api/user/register",
            array_map(
                /**
                 * @param $item
                 *
                 * @return mixed
                 */ fn($item): mixed => $item, $userData
            )
        );

        $response->assertResponseStatus(200);

        $user = User::first()->get()->toArray();

        self::assertEquals(
            Arr::except(end($user), ['id', 'api_token', 'created_at', 'updated_at']),
            Arr::except($userData, ['password'])
        );
    }

    public function testResetPassword()
    {
        $user = User::factory()
            ->create();

        $response = $this->post(
            env('APP_URL')."/api/user/recover-password",
            ['email' => $user->email]
        );

        $responseMessage = array_map(fn($item) => (array) $item, (array)json_decode($response->response->baseResponse->getContent()));
        $this->assertResponseOk();
        self::assertEquals($responseMessage['status'][0], 'passwords.sent');

        $response = $this->get(
            env('APP_URL')."/user/reset-password",
            ['email' => $user->email]
        );
        $responseMessage = array_map(fn($item) => (array) $item, (array)json_decode($response->response->baseResponse->getContent()));

        $this->assertResponseOk();
    }

    /**
     * @dataProvider userProvider
     * @return void
     */
    public function testAuthUserGetCompanies($isAuthorised): void
    {
        $user = User::factory()
            ->hasCompanies(rand(2, 6))
            ->count(10)
            ->create();

        if ($isAuthorised) {
            Auth::setUser($user->first->id);
        } else {
            Auth::guest();
        }

        $response = $this->get(env('APP_URL')."/api/user/companies");

        if (!$isAuthorised) {
            Auth::guest();
            $this->assertResponseStatus(401);
        } else {
            Auth::setUser($user->first->id);
            $this->assertResponseOk();

            $companiesFromDB = $user->find($user->first->id)->companies->toArray();
            $companiesFromResponse = array_map(fn($company) => (array) $company, json_decode($response->response->baseResponse->getContent()));

            self::assertEquals($companiesFromDB, $companiesFromResponse);
        }
    }

    public function userProvider()
    {
        return [
            'authorized user'  => [true],
            'guest'            => [false],
        ];
    }
}
