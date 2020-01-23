<?php


namespace Tests\Feature\Auth;

use Tests\Facades\UserFactory;
use App\Models\User;
use Tests\TestCase;

/**
 * Class LogoutFromAllTest
 */
class LogoutFromAllTest extends TestCase
{
    private const URI = 'auth/logout-from-all';

    /**
     * @var User
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::withTokens(4)->create();
    }

    public function test_logout_from_all(): void
    {
        $tokens = $this->user->tokens()->get()->toArray();

        $this->assertNotEmpty($tokens);

        foreach ($tokens as $token) {
            $this->assertDatabaseHas('tokens', $token);
        }

        $response = $this->actingAs($this->user)->postJson(self::URI);
        $response->assertSuccess();

        foreach ($tokens as $token) {
            $this->assertDatabaseMissing('tokens', $token);
        }
    }

    public function test_unauthorized(): void
    {
        $response = $this->postJson(self::URI);

        $response->assertUnauthorized();
    }
}
