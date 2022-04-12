<?php

namespace Tests\app\Application\EarlyAdopter;

use App\Application\EarlyAdopter\IsEarlyAdopterService;
use App\Application\UserDataSource\UserDataSource;
use App\Domain\User;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;

class IsEarlyAdopterServiceTest extends TestCase
{
    private IsEarlyAdopterService $isEarlyAdopterService;
    private UserDataSource $userDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userDataSource = Mockery::mock(UserDataSource::class);

        $this->isEarlyAdopterService = new IsEarlyAdopterService($this->userDataSource);
    }

    /**
     * @test
     */
    public function userNotFound()
    {
        $email = 'not_existing_email@email.com';

        $user = new User(9999, $email);

        $this->userDataSource
            ->expects('findByEmail')
            ->with($email)
            ->once()
            ->andThrow(new Exception('User not found'));

        $this->expectException(Exception::class);

        $this->isEarlyAdopterService->execute($email);
    }

    /**
     * @test
     */
    public function userIsNotEarlyAdopter()
    {
        $email = 'not_early_adopter@email.com';

        $user = new User(9999, $email);

        $this->userDataSource
            ->expects('findByEmail')
            ->with($email)
            ->once()
            ->andReturn($user);

        $isUserEarlyAdopter = $this->isEarlyAdopterService->execute($email);

        $this->assertFalse($isUserEarlyAdopter);
    }

    /**
     * @test
     */
    public function userIsAnEarlyAdopter()
    {
        $email = 'not_early_adopter@email.com';

        $user = new User(300, $email);

        $this->userDataSource
            ->expects('findByEmail')
            ->with($email)
            ->once()
            ->andReturn($user);

        $isUserEarlyAdopter = $this->isEarlyAdopterService->execute($email);

        $this->assertTrue($isUserEarlyAdopter);
    }
}
