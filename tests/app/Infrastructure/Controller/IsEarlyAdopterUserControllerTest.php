<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\UserDataSource\UserDataSource;
use App\Domain\User;
use Exception;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class IsEarlyAdopterUserControllerTest extends TestCase
{
    private UserDataSource $userDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userDataSource = Mockery::mock(UserDataSource::class);
        $this->app->bind(UserDataSource::class, fn () => $this->userDataSource);
    }

    /**
     * @test
     */
    public function noUserFoundForGivenEmail()
    {
        $this->userDataSource
            ->expects('findByEmail')
            ->with('another@email.com')
            ->once()
            ->andThrow(new Exception('User not found'));

        $response = $this->get('/api/user/another@email.com');

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson(['error' => 'User not found']);
    }

    /**
     * @test
     */
    public function userIsEarlyAdopter()
    {
        $user = new User(300, 'email@email.com');

        $this->userDataSource
            ->expects('findByEmail')
            ->with('email@email.com')
            ->once()
            ->andReturn($user);

        $response = $this->get('/api/user/email@email.com');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson(['earlyAdopter' => true]);
    }

    /**
     * @test
     */
    public function userIsNotEarlyAdopter()
    {
        $user = new User(99988, 'email@email.com');

        $this->userDataSource
            ->expects('findByEmail')
            ->with('email@email.com')
            ->once()
            ->andReturn($user);

        $response = $this->get('/api/user/email@email.com');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson(['earlyAdopter' => false]);
    }
}
