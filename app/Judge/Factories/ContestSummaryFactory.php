<?php namespace Judge\Factories;

use Carbon\Carbon;

use Judge\Cache\CacheWrapper;

use Judge\Models\Contest;
use Judge\Models\ContestSummary;
use Judge\Models\ContestSummaryCollection;
use Judge\Models\Problem;
use Judge\Models\User;

use Judge\Repositories\ContestRepository;
use Judge\Repositories\LanguageRepository;
use Judge\Repositories\ProblemRepository;
use Judge\Repositories\SolutionRepository;
use Judge\Repositories\SolutionStateRepository;
use Judge\Repositories\MessageRepository;

class ContestSummaryFactory
{
    public function __construct(
        ContestRepository $contests,
        ProblemRepository $problems,
        SolutionRepository $solutions,
        SolutionStateRepository $solution_states
    ) {
        $this->contests = new CacheWrapper($contests, 1);
        $this->problems = new CacheWrapper($problems, 1);
        $this->solutions = new CacheWrapper($solutions, 1);
        $this->solution_states = new CacheWrapper($solution_states, 10);
    }

    public function make(Contest $contest)
    {
        $collection = new ContestSummaryCollection();

        foreach ($this->contests->teamsForContest($contest) as $team) {
            $collection->push($this->makeForTeam($contest, $team));
        }

        return $collection;
    }

    public function makeForTeam(Contest $contest, User $user)
    {
        $summary = new ContestSummary;

        $summary->user = $user;
        
        $summary->problems_solved = $this->problemsSolved($contest, $user);

        $summary->penalty_points = $this->totalPoints($contest, $user);

        $summary->problem_summaries = array();

        foreach ($contest->problems as $problem) {
            $problem_info = array();
            $problem_info['points_for_problem'] = $this->pointsForProblem($problem, $user, $contest);
            $problem_info['num_submissions'] =
                $this->solutions->incorrectSubmissionCountFromUserFromProblem($user, $problem)
                + $this->solutions->hasCorrectSolutionFromUser($user, $problem);

            $summary->problem_summaries[] = $problem_info;
        }
        return $summary;
    }

    /*
     * Calculates the number of problems solved for user in a provide
     * contest. If no contest is provided, defaults to the most recent current
     * contest.
     *
     * @param Contest $contest
     * @return int
     */
    public function problemsSolved(Contest $contest, User $user)
    {
        // Loop over every problem, checking if there is a solution
        // that is solved
        $total = 0;
        foreach ($this->problems->forContest($contest) as $problem) {
            $total += $this->solutions->hasCorrectSolutionFromUser($user, $problem);
        }

        // now sum up the solved states
        return $total;
    }
    
    /**
     * Scores the solutions submitted by a user for a
     * given contest.
     *
     * @param contest $contest the contest to score points on
     * @return int the total number of points for this user
     */
    public function totalPoints(Contest $contest, User $user)
    {
        $problems = $this->problems->forContest($contest);
        $points = 0;
        foreach ($problems as $problem) {
            $points += $this->pointsForProblem($problem, $user, $contest);
        }
        return $points;
    }

    /**
     * Scores the user's solution for this problem.
     * 20 pts added for each incorrect solution, plus 1 pt
     * for each additional minute since contest start time.
     *
     * @param problem $problem the problem to score
     * @return int the number of points
     */
    public function pointsForProblem(Problem $problem, User $user, Contest $contest = null)
    {
        if ($contest == null) {
            $contest = $problem->contest;
        }

        // if they didn't solve it, return 0
        if (! $this->solutions->hasCorrectSolutionFromUser($user, $problem)) {
            return 0;
        }

        $incorrect_count = $this->solutions->incorrectSubmissionCountFromUserFromProblem($user, $problem);

        $earliest_solution = $this->solutions->earliestCorrectSolutionFromUserForProblem($user, $problem);

        $minutes_since_contest = $earliest_solution->created_at->diffInMinutes($contest->starts_at);
        
        return $incorrect_count * 20 + $minutes_since_contest;
    }
}
