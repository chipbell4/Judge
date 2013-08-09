<?php

class SolutionController extends BaseController {

	public function teamIndex() {
		$problems = array();

		return View::make('solutions_team')
			->with('solutions', Solution::forCurrentContest()->where('user_id', Sentry::getUser()->id)->get())
			->with('problems', Problem::lists('name', 'id'));
	}

	public function judgeIndex() {
		return View::make('solutions_judge')
			->with('solutions', Solution::forCurrentContest()->unjudged()->unclaimed()->get());
	}

	/**
	 * Saves an uploaded submission
	 *
	 * @return Response
	 */
	public function store()
	{
		$solution_state_id = SolutionState::pending()->id;

		$solution = new Solution();
		$solution->problem_id = Input::get('problem_id');
		$solution->user_id = Sentry::getUser()->id;
		$solution->solution_state_id = $solution_state_id;
		$solution->processUpload('solution_code', 'solution_code', 'solution_filename', 'solution_language');
		if(!$solution->save()) {
			Session::flash('error', $solution->errors());
		}

		return Redirect::route('team_index');
	}

	/**
	 * Shows the update form for a submission, also forces a judge
	 * to claim the submission
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		// check that the solution isn't claimed already, and
		// make the current judge claim it...
		$solution = Solution::find($id);
		if($solution->claiming_judge_id != null) {
			Session::flash('error', 'That solution has already been claimed by ' . $solution->claiming_judge->username);
			return Redirect::route('judge_index');
		}
		$solution->claiming_judge_id = Sentry::getUser()->id;
		if(!$solution->save()) {
			Session::flash('error', $solution->errors());
			$solution->claiming_judge_id = null;
		}

		// return the form
		return View::make('forms.edit_solution')
			->with('solution', $solution)
			->with('solution_states', SolutionState::lists('name','id'));
	}

	/**
	 * Updates the status of a submission
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$unjudged_state = SolutionState::pending();

		$s = Solution::find($id);
		if($s->claiming_judge_id == null && $s->solution_state_id == $unjudged_state->id) {
			$s->solution_state_id = Input::get('solution_state_id');
			if(!$s->save()) {
				Session::flash('error', $s->errors());
			}
		}

		return Redirect::route('judge_index');
	}
}