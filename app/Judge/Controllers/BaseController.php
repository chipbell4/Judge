<?php namespace Judge\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

use Judge\Repositories\ContestRepository;
use Judge\Repositories\LanguageRepository;
use Judge\Repositories\ProblemRepository;
use Judge\Repositories\SolutionRepository;
use Judge\Repositories\SolutionStateRepository;
use Judge\Repositories\MessageRepository;

class BaseController extends Controller
{
    public function __construct(
        ContestRepository $contests,
        LanguageRepository $languages,
        ProblemRepository $problems,
        SolutionRepository $solutions,
        SolutionStateRepository $solution_states,
        MessageRepository $messages
    ) {
            
        $this->contests = $contests;
        $this->languages = $languages;
        $this->problems = $problems;
        $this->solutions = $solutions;
        $this->solution_states = $solution_states;
        $this->messages = $messages;

        $this->bindContestName();
    }

    /**
     * Binds a contest name for any view, so that the layout works
     *
     * @return void
     */
    protected function bindContestName()
    {
        $contest_name = 'Judge';

        if (!is_null($contest_name)) {
            $contest_name = $this->contests
                ->firstCurrent()
                ->name;
        }

        View::share('contest_name', $contest_name);
    }
}
