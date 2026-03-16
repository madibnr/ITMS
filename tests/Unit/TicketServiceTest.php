<?php

namespace Tests\Unit;

use App\Services\TicketService;
use Tests\TestCase;

class TicketServiceTest extends TestCase
{
    private TicketService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TicketService();
    }

    public function test_sla_hours_for_critical_priority(): void
    {
        $deadline = $this->service->calculateSlaDeadline('Critical');
        $expectedHours = 4;

        // Allow 1 minute tolerance for test execution time
        $this->assertEqualsWithDelta(
            now()->addHours($expectedHours)->timestamp,
            $deadline->getTimestamp(),
            60
        );
    }

    public function test_sla_hours_for_high_priority(): void
    {
        $deadline = $this->service->calculateSlaDeadline('High');
        $expectedHours = 8;

        $this->assertEqualsWithDelta(
            now()->addHours($expectedHours)->timestamp,
            $deadline->getTimestamp(),
            60
        );
    }

    public function test_sla_hours_for_medium_priority(): void
    {
        $deadline = $this->service->calculateSlaDeadline('Medium');
        $expectedHours = 24;

        $this->assertEqualsWithDelta(
            now()->addHours($expectedHours)->timestamp,
            $deadline->getTimestamp(),
            60
        );
    }

    public function test_sla_hours_for_low_priority(): void
    {
        $deadline = $this->service->calculateSlaDeadline('Low');
        $expectedHours = 48;

        $this->assertEqualsWithDelta(
            now()->addHours($expectedHours)->timestamp,
            $deadline->getTimestamp(),
            60
        );
    }

    public function test_sla_defaults_to_24_hours_for_unknown_priority(): void
    {
        $deadline = $this->service->calculateSlaDeadline('Unknown');
        $expectedHours = 24;

        $this->assertEqualsWithDelta(
            now()->addHours($expectedHours)->timestamp,
            $deadline->getTimestamp(),
            60
        );
    }

    public function test_sla_constants_are_correct(): void
    {
        $this->assertEquals([
            'Critical' => 4,
            'High' => 8,
            'Medium' => 24,
            'Low' => 48,
        ], TicketService::SLA_HOURS);
    }
}
