<?php

class Problem extends Base {
	public static $rules = array(
		'name' => 'required',
		'contest_id' => 'required',
		'judging_input' => 'required',
		'judging_output' => 'required'
		);

	public function contest() {
		return $this->belongsTo('Contest');
	}

	public function solutions() {
		return $this->hasMany('Solution');
	}

	// public function setJudgingInputAttribute($filename) {
	// 	$this->readFile('judging_input', null, null);
	// 	// $filename = "/tmp/$filename";
	// 	// list($original, $ext, $file_contents, $tmp_path) = Base::unpackFile($filename, true);
	// 	// Log::debug("FILENAME: $filename, FILE CONTENTS:\n$file_contents");
	// 	// $this->attributes['judging_input'] = $file_contents;
	// }

	// public function setJudgingOutputAttribute($filename) {
	// 	$this->readFile('judging_output', null, null);
	// 	// $filename = "/tmp/$filename";
	// 	// list($original, $ext, $file_contents, $tmp_path) = Base::unpackFile($filename, true);
	// 	// $this->attributes['judging_output'] = $file_contents;
	// }

	public function scopeForCurrentContest() {
		$contests = Contest::current()->first();
		if($contests == null) {
			return Problem::where('id', null);
		}
		return Problem::where('contest_id', $contests->id)->orderBy('created_at');
	}
}