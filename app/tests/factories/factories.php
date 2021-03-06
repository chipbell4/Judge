<?php

$factory('Judge\Models\Contest', 'contest', [
    'name' => $faker->sentence(2),
    'starts_at' => Carbon\Carbon::now()->subDay(),
    'ends_at' => Carbon\Carbon::now()->addDay()
]);

$factory('Judge\Models\Message', 'message', [
    'contest_id' => 'factory:contest',
    'problem_id' => 'factory:problem',
    'text' => $faker->paragraph,
    'sender_id' => 'factory:team',
    'response_text' => $faker->paragraph,
    'responder_id' => 'factory:judge',
]);

$factory('Judge\Models\Message', 'global_message', [
    'contest_id' => 'factory:contest',
    'problem_id' => 'factory:problem',
    'text' => $faker->paragraph,
    'sender_id' => 'factory:judge',
    'response_text' => null,
    'responder_id' => null,
]);

$factory('Judge\Models\Problem', 'problem', [
    'name' => $faker->sentence(2),
    'contest_id' => 'factory:contest',
    'judging_input' => $faker->paragraph(),
    'judging_output' => $faker->paragraph()
]);

$factory('Judge\Models\Solution', 'solution', [
    'problem_id' => 'factory:problem',
    'user_id' => 'factory:team',
    'language_id' => 1,
    'solution_state_id' => 1,
    'solution_code' => $faker->word,
    'solution_filename' => $faker->word
]);

$factory('Judge\Models\User', 'team', [
    'username' => $faker->word,
    'password' => 'password',
    'team' => true,
    'api_key' => $faker->word,
]);

$factory('Judge\Models\User', 'judge', [
    'username' => $faker->word,
    'password' => 'password',
    'judge' => true,
    'api_key' => $faker->word,
]);
