<?php

class Survey
{

    public static $default_instructions = [
                                'good'=> 'Describe at least one specific situation where __competency_name__ has demonstrated grasp of this skill/value, and what impact it had on the work being done.',
                                'bad'=> ' Describe at least one specific situation where __competency_name__ has failed to demonstrated grasp of this skill/value, what impact it had on the work being done, and how he can improve it.'
                            ];

    public static function create($form)
    {
        $required_fields = ['name' => 'Survey Name', 'org_id' => 'Org Name', 'team_id' => 'Team Name', 'competencies'=> 'Competencies'];
        $errors = Util::validate_form_contains_required_fields($form, $required_fields);

        if (!empty($errors)) return ['status'=>'error', 'value'=>$errors];

        $aggregated_score = false;
        if(array_key_exists('aggregated_score', $form) and $form['aggregated_score']=='yes')
            $aggregated_score = true;

        $now = date('Y-m-d H:i:s');
        try{
            DB::insert('survey', ['name'=>$form['name'], 'org_id'=>$form['org_id'], 'team_id'=>$form['team_id'], 'aggregated_score'=>$aggregated_score, 'username'=>Session::username(), 'created'=>$now]);
        }catch (MeekroDBException $e) {
            return ['status'=>'error', 'value'=>$e->getMessage()];
        }
        $survey_id = DB::insertId();

        $competency_mapping = [];
        foreach($form['competencies'] as $competency) {
            $competency_mapping[] = ['survey_id'=>$survey_id, 'competency_id'=>$competency];
        }
        DB::insert('survey_competencies', $competency_mapping);

        return ['status'=>'success', 'value'=>$survey_id];
    }

    public static function owner($survey_id)
    {
        return DB::queryFirstField("select username from survey where survey.id=%i LIMIT 1", $survey_id);
    }

    public static function fetch_my_surveys()
    {
        return DB::query("select survey.*, org.name as org_name, team.name as team_name from survey INNER JOIN org on org.id=org_id INNER JOIN team on team.id=team_id where survey.username=%s order by created desc", Session::username());
    }

    public static function is_owned_by($survey_id)
    {
        return Session::username() == self::owner($survey_id);
    }

    public static function fetch_competencies_for($survey_id){
        return DB::query("SELECT survey_competencies.competency_id as id, 
                                competencies.name as name, 
                                competencies.description as description, 
                                survey_competencies.instructions_for_good as instructions_for_good, 
                                survey_competencies.instructions_for_bad as instructions_for_bad 
                                FROM survey_competencies 
                                INNER JOIN competencies on survey_competencies.competency_id=competencies.id 
                                WHERE survey_competencies.survey_id = %i ", $survey_id);
    }

    public static function fetch_survey($survey_id){
        return DB::queryFirstRow("select * from survey where survey.id=%i", $survey_id);
    }

    public static function update_competency_instructions($survey_id,$competency_id,$instructions){
        $instructions_for_good = $instructions['good'];
        $instructions_for_bad = $instructions['bad'];
        return DB::query("UPDATE survey_competencies SET 
                            instructions_for_good = %s, 
                            instructions_for_bad = %s 
                            WHERE survey_id = %i 
                            AND competency_id = %i",
                            $instructions_for_good, $instructions_for_bad, $survey_id, $competency_id);
    }
}
