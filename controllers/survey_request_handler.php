<?php

use phpish\app;
use phpish\template;

include_once MODELS_DIR . 'competencies.php';
include_once MODELS_DIR . 'survey.php';
include_once MODELS_DIR . 'review.php';
include_once MODELS_DIR . 'feedback.php';
include_once MODELS_DIR . 'team.php';

app\any("/survey[/.*]", function ($req) {
    if(Session::is_inactive()) {
        set_flash_msg('error', 'You need to login to perform this action.');
        $url = $req['path'];
        return app\response_302(BASE_URL.'auth/login?requested_url='.$url);
    }
    return app\next($req);
});

app\get("/survey", function ($req) {
    $data = Survey::fetch_my_surveys();
    return template\compose("survey/dashboard.html", compact('data'), "layout.html");
});

app\any("/survey/create", function ($req) {
    if(Team::does_not_belong_to_any_org()) {
        set_flash_msg('error', 'You need to be part of at least one organisation to perform this action.');        
        return app\response_302(BASE_URL.'org/create?requested_url='.$req['path']);
    }
    if(Team::not_a_manager()) {
        set_flash_msg('error', 'You need to be a manager of at least one team to perform this action.');
        return app\response_302(BASE_URL.'team/create?requested_url='.$req['path']);
    }
    return app\next($req);
});

app\get("/survey/create", function ($req) {
    $data = ['teams'=>Team::orgs_and_teams_managed_by_me(), 'competencies'=>Competencies::fetch_all()];
    return template\compose("survey/create.html", compact('data'), "layout.html");
});

app\get("/survey/competency/{competency_id}/edit", function ($req) {
    $competency_id = $req['matches']['competency_id'];
    $data = ['competency_data' => Competencies::get_full_data($competency_id), 'default_grading' => Review::$ratings];
    return template\compose("survey/manage_competency_grading.html", compact('data'), "layout.html");
});

app\post("/survey/competency/{competency_id}/edit", function ($req) {
    $competency_id = $req['matches']['competency_id'];
    if (isset($req['form']['enable_default_grading'])){
        Competencies::delete_competency_grades($competency_id);
    }else{
        //update, create
        $grades = array();
        $grades[0] = $req['form']['grade_0_field'];
        $grades[1] = $req['form']['grade_1_field'];
        $grades[2] = $req['form']['grade_2_field'];
        $grades[3] = $req['form']['grade_3_field'];
        $grades[4] = $req['form']['grade_4_field'];
        $grades[5] = $req['form']['grade_5_field'];
        Competencies::update_competency_grades($competency_id,$grades);
    }
    set_flash_msg('success', 'Competencies Grading Updated');
    $data = ['competency_data' => Competencies::get_full_data($competency_id), 'default_grading' => Review::$ratings];
    return template\compose("survey/manage_competency_grading.html", compact('data'), "layout.html");
});

app\get("/survey/{id}/edit-competencies", function ($req) {
    $survey_id = $req['matches']['id'];
    $competencies = Survey::fetch_competencies_for($survey_id);
    $survey_info = Survey::fetch_survey($survey_id);
    if(empty($competencies)){
        set_flash_msg('error', 'Sorry! There are no competencies identified for giving feedback in this survey. Please contact your manager.');
        return app\response_302(BASE_URL."review/pending");
    }
    $data = ['competencies' => $competencies, 'default_instructions' => Survey::$default_instructions, 'survey_info' => $survey_info];
    return template\compose("survey/manage_competency_instructions.html", compact('data'), "layout.html");
});

app\post("/survey/{id}/competency/{competency_id}/edit", function ($req) {
    $competency_id = $req['matches']['competency_id'];
    $survey_id = $req['matches']['id'];
    $instructions = array();
    if (isset($req['form']['using_customized_instructions'.$competency_id])){
        //update, create
        $instructions['good'] = $req['form']['instructions_good'];
        $instructions['bad'] = $req['form']['instructions_bad'];        
    }else{
        $instructions['good'] = '';
        $instructions['bad'] = '';
    }
    Survey::update_competency_instructions($survey_id,$competency_id,$instructions);
    set_flash_msg('success', 'Competency Instructions Updated');
    return app\response_302(BASE_URL."survey/$survey_id/edit-competencies");
});

app\post("/survey/create", function ($req) {
    $response = Survey::create($req['form']);
    if($response['status']!='success') {
        set_flash_msg($response['status'], $response['value']);
        return app\response_302(BASE_URL.'survey/create');
    }
    $survey_id = $response['value'];
    $survey_name = $req['form']['name'];
    $org_id = $req['form']['org_id'];
    $team_id = $req['form']['team_id'];
    $employees = Team::all_members_from($org_id, $team_id);
    $team_members = Team::team_members_from($org_id, $team_id);
    $data = ['survey_id'=>$survey_id, 'survey_name'=>$survey_name, 'org_id'=>$org_id, 'team_id'=>$team_id, 'employees'=>$employees, 'team_members'=>$team_members];
    return template\compose("survey/assign_reviewers.html", compact('data'), "layout.html");
});

app\post("/survey/add-new", function ($req) {
    return Competencies::add_new($_POST['name'], $_POST['description']);
});

app\any("/survey/{id}/[overview|add-reviewers|edit-reviewers|reviewee/{reviewee_name}]", function ($req) {
    $survey_id = $req['matches']['id'];
    if(!Survey::is_owned_by($survey_id)){
        set_flash_msg('error', 'You are not authorised to view or update this survey');
        return app\response_302(BASE_URL.'survey/create');
    }
    return app\next($req);
});

app\post("/survey/{id}/add-reviewers", function ($req) {
    $survey_id = $req['matches']['id'];
    $response = Review::update_reviewers($survey_id, $req['form']);
    set_flash_msg($response['status'], $response['value']);
    if($response['status']!='success') {
        $survey_name = $req['form']['survey_name'];
        $org_id = $req['form']['org_id'];
        $team_id = $req['form']['team_id'];
        $employees = Team::all_members_from($org_id, $team_id);
        $team_members = Team::team_members_from($org_id, $team_id);
        $data = ['survey_id'=>$survey_id, 'survey_name'=>$survey_name, 'org_id'=>$org_id, 'team_id'=>$team_id, 'employees'=>$employees, 'team_members'=>$team_members];
        return template\compose("survey/assign_reviewers.html", compact('data'), "layout.html");
    }
    return app\response_302(BASE_URL.'survey/'.$survey_id ."/overview");
});

app\get("/survey/{id}/edit-reviewers", function ($req) {
    $survey_id = $req['matches']['id'];
    $data = Review::current_assignment($survey_id);
    return template\compose("survey/update_reviewers.html", compact('data'), "layout.html");
});

app\post("/survey/{id}/edit-reviewers", function ($req) {
    $survey_id = $req['matches']['id'];
    $response = Review::update_reviewers($survey_id, $req['form']);
    set_flash_msg($response['status'], $response['value']);
    $url_dest = 'overview';
    if($response['status']!='success') {
        $url_dest = "edit-reviewers";
    }
    return app\response_302(BASE_URL.'survey/'.$survey_id ."/".$url_dest);
});

app\get("/survey/{id}/overview", function ($req) {
    $id = $req['matches']['id'];
    $data = Review::details_grouped_by_reviewee($id);
    return template\compose("survey/details.html", compact('data'), "layout.html");
});

app\get("/survey/{id}/reviewee/{reviewee_name}", function ($req) {
    $id = $req['matches']['id'];
    $reviewee_name = $req['matches']['reviewee_name'];
    $data = Feedback::fetch_consolidated_reviewee_feedback_for($id, $reviewee_name, true);
    return template\compose("feedback/reviewee.html", compact('data'), "layout.html");
});