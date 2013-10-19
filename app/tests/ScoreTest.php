<?php

use Carbon\Carbon as Carbon;

class ScoreTest extends TestCase {

	public function setUp() {
		parent::setUp();

		// Add a contest and team to work with, then attach them
		$this->contest = $this->createContest('Contest Test');
		$this->team = $this->createTeam('Team Test', $this->contest->id);
		$this->contest->users()->attach($this->team);

		// attach first problem to contest
		$this->problem = Problem::find(1);
		$this->problem->contest_id = $this->contest->id;
		$this->problem->save();

		// get the solution we want to work with
		// we'll just attempt with the first solution in the database,
		// which we assume to be a seed value anyways
		// TODO: Make this more robust instead of ASSUMING!
		$this->solution = $this->createSolution($this->team, Problem::first());
	}

	public function tearDown() {
		$this->solution->delete();
		DB::table('users')->where('username', 'LIKE', 'Team Test')->delete();
		DB::table('contests')->where('name', 'LIKE', 'Contest Test')->delete();
	}

	public function testScoreIsZeroUponInit() {
		// Test that the team's initial score is 0
		$this->assertEquals($this->team->totalPoints($this->contest), 0);
	}

	public function testScoreIsZeroUponPendingOrIncorrect() {
		// Set a solution to be Pending and submitted one hour after contest start
		$this->solution->solution_state_id = SolutionState::pending()->id;
		$this->solution->created_at = Carbon::now()->subDay(1)->addHour();
		$this->solution->user_id = $this->team->id;
		$this->assertEquals($this->team->totalPoints($this->contest), 0);

		$this->solution->solution_state_id = SolutionState::find(2)->id;
		$this->assertEquals($this->team->totalPoints($this->contest), 0);
	}

	public function testScoreIsAccurateUponCorrect() {
		$this->solution->solution_state_id = SolutionState::where('name', 'LIKE', '%correct%')->first()->id;
		$this->solution->created_at = (new Carbon($this->contest->starts_at))->addHour()->format('Y-m-d H:i:s');
		$this->solution->save();

		$this->assertEquals(60, $this->team->totalPoints($this->contest));
	}

	//public function testMultipleContestsDoNotAffectScore() {	
	//}

	/**
	 * Creates a temporary contest to test with
	 *
	 * @param string $contest_name The name of the contest to be created
	 * @return Contest The contest that was created
	 */
	private function createContest($contest_name) {
		$yesterday = Carbon::now()->subDay(1);
		$contest = new Contest();
		$contest->name = $contest_name;
		$contest->starts_at = $yesterday->format("Y-m-d H:i:s");
		$contest->ends_at = $yesterday->addDays(3)->format("Y-m-d H:i:s");
		$contest->save();
		return $contest;
	}

	/**
	 * Creates a team to test with
	 *
	 * @param string $username The name for this team
	 * @param int $contest_id The id for the team's contest
	 * @return User $user The team that was created
	 */
	private function createTeam($username, $contest_id) {
		$user = new User();
		$user->username = $username;
		$user->password = 'secret';
		$user->admin = false;
		$user->judge = false;
		$user->team = true;
		$user->contests()->sync(array($contest_id));
		$user->save();
		return $user;
	}

	/**
	 * Creates a test solution associated with a user
	 *
	 * @param User $user A user to attach
	 * @param Problem $problem A problem to associate the solution with
	 */
	private function createSolution(User $user, Problem $problem) {
		$solution = new Solution();
		$solution->user_id = $user->id;
		$solution->problem_id = $problem->id;
		$solution->solution_code = 'asdfafds';
		$solution->language_id = Language::first()->id;
		$solution->solution_filename = 'hello.py';
		$solution->solution_state_id = 7;
		$solution->save();
		return $solution;
	}
}
