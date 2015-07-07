<?php

class Competencies
{
    public static function fetch_all()
    {
        return DB::query("SELECT `id`, name, description FROM competencies WHERE owner_name IN ('all', '".Session::username()."') ORDER BY use_count DESC");
    }

    public static function count_for($review_id)
    {
        return DB::queryFirstField("SELECT count(competency_id) as records from survey_competencies where survey_id=(SELECT survey_id from reviews where id=%i)", $review_id);
    }

    public static function add_new($name, $description)
    {
    	return DB::query("INSERT INTO `competencies` (`name`, `description`, `owner_name`) VALUES ('".$name."', '".$description."', '".Session::username()."')");
    }

    public static function get_full_data($competency_id){
      $response = array();
      $response['base_data'] = DB::queryFirstRow("SELECT * FROM `competencies` WHERE id = $competency_id");
      $response['custom_grading'] = DB::query("SELECT * FROM competencies_grading WHERE competency_id = $competency_id ORDER BY grade_position ASC");
      return $response;
    }

    public static function delete_competency_grades($competency_id){
        return DB::query("DELETE FROM `competencies_grading` WHERE competency_id = $competency_id");
    }

    public static function update_competency_grades($competency_id, $grades){
        self::delete_competency_grades($competency_id);
        foreach ($grades as $grade_position => $grade_text) {
            DB::query("INSERT INTO `competencies_grading` (`competency_id`,`grade_position`,`grade_text`) VALUES ( $competency_id, $grade_position, '$grade_text' ) ");
        }
    }
}
