<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Laracasts\TestDummy\Factory;

class DbMessageRepositoryTest extends DbTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->repo = App::make('Judge\Repositories\MessageRepository');
    }

    public function testFromJudgeToTeam()
    {
        $judge = Factory::create('judge');
        $team = Factory::create('team');
        Factory::create('message', ['sender_id' => $judge->id, 'responder_id' => $team->id]);

        $this->repo->fromJudgeToTeam($team);
        $this->assertCount(1, $this->repo->fromJudgeToTeam($team));
    }

    public function testFromJudgeToTeamWithGlobalMessage()
    {
        $team = Factory::create('team');
        Factory::create('global_message');
        
        $this->assertCount(1, $this->repo->fromJudgeToTeam($team));
    }

    public function testFromJudgeToTeamWithOtherTeam()
    {
        $judge = Factory::create('judge');
        $other_team = Factory::create('team');
        $team = Factory::create('team');
        Factory::create('message', ['sender_id' => $judge->id, 'responder_id' => $other_team->id]);
        
        $this->assertCount(0, $this->repo->fromJudgeToTeam($team));
    }

    public function testUnrespondedWithNoMatches()
    {
        // the message by default has a responder
        Factory::create('message');
        $this->assertCount(0, $this->repo->unresponded());
    }

    public function testUnrespondedWithMatches()
    {
        // Create a message with no responder
        $message = Factory::create('message', ['responder_id' => null]);
        $this->assertCount(1, $this->repo->unresponded());
    }

    public function testUnrespondedDoesntGetJudgeMessages()
    {
        $message = Factory::create('global_message', ['responder_id' => null]);
        $this->assertCount(0, $this->repo->unresponded());
    }

    public function testUnrespondedForCorrectSorting()
    {
        $contest = Factory::create('contest');
        $message_1 = Factory::create('message', ['responder_id' => null, 'created_at' => Carbon::now()->subDay(), 'contest_id' => $contest->id]);
        $message_2 = Factory::create('message', ['responder_id' => null, 'created_at' => Carbon::now(), 'contest_id' => $contest->id]);

        $results = $this->repo->unresponded();

        // Results should appear in chronological order
        $this->assertEquals($message_1->id, $results[0]->id);
        $this->assertEquals($message_2->id, $results[1]->id);
    }

    public function testUnrespondedForOtherContest()
    {
        $message_1 = Factory::create('message', ['responder_id' => null]);

        $otherContest = Factory::create('contest');

        $results = $this->repo->unresponded($otherContest);

        $this->assertCount(0, $results);
    }

    public function testFrom()
    {
        $judge = Factory::create('judge');
        $team = Factory::create('team');
        $message = Factory::create('message', ['sender_id' => $judge->id]);

        $results = $this->repo->from($judge);
        $this->assertEquals($judge->id, $results[0]->sender_id);

        $this->assertCount(0, $this->repo->from($team));
    }

    public function testFromDifferentContest()
    {
        $contest = Factory::create('contest');
        $judge = Factory::create('judge');
        $message = Factory::create('message', ['sender_id' => $judge->id]);

        $this->assertCount(0, $this->repo->from($judge, $contest));
    }
}
