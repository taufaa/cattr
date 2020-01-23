<?php

namespace Tests\Feature\TimeUseReport;

use App\Models\TimeInterval;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Tests\Facades\IntervalFactory;
use Tests\Facades\UserFactory;
use Tests\TestCase;

/**
 * Class ListTest
 */
class ListTest extends TestCase
{
    private const URI = 'v1/time-use-report/list';

    private const INTERVALS_AMOUNT = 10;

    /**
     * @var Collection
     */
    private $intervals;

    /**
     * @var User
     */
    private $admin;

    /**
     * @var int
     */
    private $duration;

    /**
     * @var array
     */
    private $userIds;
    /**
     * @var array
     */
    private $requestData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = UserFactory::asAdmin()->withTokens()->create();

        $this->intervals = IntervalFactory::withRandomRelations()->createMany(self::INTERVALS_AMOUNT);

        $this->intervals->each(function (TimeInterval $interval) {
            $this->userIds[] = $interval->user_id;
            $this->duration += Carbon::parse($interval->end_at)->diffInSeconds($interval->start_at);
        });

        $this->requestData = [
            'start_at' => $this->intervals->min('start_at'),
            'end_at' => Carbon::create($this->intervals->max('end_at'))->addMinute(),
            'user_ids' => $this->userIds
        ];
    }

    public function test_list(): void
    {
        $response = $this->actingAs($this->admin)->postJson(self::URI, $this->requestData);
        $response->assertOk();

        $totalTime = array_sum(array_column($response->json(), 'total_time'));

        $this->assertEquals($this->duration, $totalTime);

        //TODO change later
    }

    public function test_unauthorized(): void
    {
        $response = $this->getJson(self::URI);

        $response->assertUnauthorized();
    }

    public function test_without_params(): void
    {
        $response = $this->actingAs($this->admin)->getJson(self::URI);

        $response->assertValidationError();
    }
}
