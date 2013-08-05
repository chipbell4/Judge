<?php

class SolutionController extends BaseController {

	public function teamIndex() {
		return "HEY";
	}

	public function judgeIndex() {
		return View::make('judge')
			->with('solutions', Solution::forCurrentContest()->unjudged()->unclaimed()->get());
	}

	/**
	 * Saves an uploaded submission
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Shows the update form for a submission, also forces a judge
	 * to claim the submission
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		// TODO: this will be moved to a route filter
		if(!Sentry::check()) {
			App::abort(403);
		}
		$user = Sentry::getUser();
		if(!$user->judge && !$user->admin) {
			App::abort(403);
		}

		// check that the solution isn't claimed already, and
		// make the current judge claim it...
		$solution = Solution::find($id);
		if($solution->claiming_judge_id != null) {
			// TODO: Session flash that problem has been claimed by Judge X
			return Redirect::to('/judge');
		}
		$solution->claiming_judge_id = $user->id;
		$solution->save();

		$solution_states = array();
		foreach(SolutionState::all() as $solution_state) {
			$solution_states[$solution_state->id] = $solution_state->name;
		}

		// return the form
		return View::make('forms.edit_solution')
			->with('solution', $solution)
			->with('solution_states', $solution_states);
	}

	/**
	 * Updates the status of a submission
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		// TODO: Validate
		$s = Solution::find($id);
		$s->solution_state_id = Input::get('solution_state_id');
		$s->save();

		// TODO: Use named routes
		return Redirect::to('/judge');
	}
}