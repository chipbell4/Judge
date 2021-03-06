<?php

use Carbon\Carbon;
use Judge\Models\Contest;
use Judge\Models\Problem;
use Judge\Models\Solution;
use Judge\Models\SolutionState;

class DbSolutionTest extends DbTestCase
{
    protected function stubSolution()
    {
        $contest = Contest::create([
            'name' => 'test contest',
            'starts_at' => Carbon::yesterday(),
            'ends_at' => Carbon::tomorrow()
        ]);

        $problem = Problem::create([
            'name' => 'test problem',
            'contest_id' => $contest->id,
            'judging_input' => 'INPUT',
            'judging_output' => 'OUTPUT'
        ]);

        return Solution::create([
            'problem_id' => $problem->id,
            'user_id' => 1,
            'language_id' => 1,
            'solution_state_id' => 1,
            'solution_code' => 'asdf',
            'solution_filename' => 'asdf'
        ]);
    }

    public function testScopeUnclaimed()
    {
        $solution = $this->stubSolution();
        $solution->claiming_judge_id = null;
        $solution->save();

        $this->assertEquals(1, Solution::unclaimed()->count());
    }

    public function testScopeUnclaimedWithClaimed()
    {
        $solution = $this->stubSolution();
        $solution->claiming_judge_id = 1;
        $solution->save();

        $this->assertEquals(0, Solution::unclaimed()->count());
    }
}
