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
}
