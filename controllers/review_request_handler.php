<?php

use phpish\app;
use phpish\template;


include_once MODELS_DIR . 'competencies.php';
include_once MODELS_DIR . 'review.php';
include_once MODELS_DIR . 'feedback.php';
include_once MODELS_DIR . 'survey.php';

app\any("/review[/.*]", function ($req) {
    if(Session::is_inactive()) {
        set_flash_msg('error', 'You need to login to perform this action.');
        return app\response_302(BASE_URL.'auth/login?requested_url='.rawurlencode($_SERVER["REQUEST_URI"]));
    }
    return app\next($req);
});

app\get("/review", function ($req) {
    $data = [];
    return template\compose("review/dashboard.html", compact('data'), "layout.html");
});

app\get("/review/pending", function ($req) {
    $data = Review::pending();
    return template\compose("review/pending.html", compact('data'), "layout.html");
});

app\get("/review/given", function ($req) {
    $data = Review::given();
    return template\compose("review/given.html", compact('data'), "layout.html");
});

app\get("/review/received", function ($req) {
    $data = Review::received();
    return template\compose("review/received.html", compact('data'), "layout.html");
});

app\any("/review/[give|update]/{id}", function ($req) {
    $id = $req['matches']['id'];
    if(!Review::am_i_the_reviewer_for($id)) {
        set_flash_msg('error', 'You are not authorised to provide feedback on this review.');
        return app\response_302(BASE_URL.'review/pending');
    }
    return app\next($req);
});

app\get("/review/give/{id}", function ($req) {
    $review_id = $req['matches']['id'];
    $competencies = Review::fetch_competencies_for($review_id);
    if(empty($competencies)){
        set_flash_msg('error', 'Sorry! There are no competencies identified for giving feedback. Please contact your manager.');
        return app\response_302(BASE_URL."review/pending");
    }else{
        foreach ($competencies as $key => $a_comp) {
            $competencies[$key]['full_data'] = Competencies::get_full_data($a_comp['competency_id']);
        }
    }
    $reviewee_name = Review::fetch_reviewee_name_for($review_id);
    $data = ['competencies'=> $competencies, 'ratings'=> Review::$ratings, 'reviewee_name'=>$reviewee_name, 'title'=>'Give', 'post_url'=>BASE_URL.'review/give/'.$review_id, 'cancel_url'=>BASE_URL.'review/pending'];
    $data['default_competencies_instructions'] = Survey::$default_instructions;
    return template\compose("review/give_feedback.html", compact('data'), "layout.html");
});

app\get("/review/update/{id}", function ($req) {
    $review_id = $req['matches']['id'];
    $data = Feedback::fetch_for($review_id);
    if(empty($data)){
        set_flash_msg('error', 'We cannot find this review. Are you sure you saved it?');
        return app\response_302(BASE_URL."review/given");
    }
    $additional_data = ['ratings'=> Review::$ratings, 'title'=>'Update', 'post_url'=>BASE_URL.'review/update/'.$review_id, 'cancel_url'=>BASE_URL.'review/given'];
    $data = array_merge($data, $additional_data);
    $competencies = $data['competencies'];
    $competencies_data = Review::fetch_competencies_for($review_id);
    foreach ($competencies as $key => $a_comp) {
        $competencies[$key]['full_data'] = Competencies::get_full_data($a_comp['competency_id']);
        foreach ($competencies_data as $a_survey_competency_data) {
            if ($a_comp['competency_id'] == $a_survey_competency_data['competency_id']){
                $competencies[$key]['instructions_for_good'] = $a_survey_competency_data['instructions_for_good'];
                $competencies[$key]['instructions_for_bad'] = $a_survey_competency_data['instructions_for_bad'];
                break;
            }
        }
    }
    $data['competencies'] = $competencies;
    $data['default_competencies_instructions'] = Survey::$default_instructions;
    return template\compose("review/give_feedback.html", compact('data'), "layout.html");
});

app\post("/review/{action:(give|update)}/{id}", function ($req) {
    $id = $req['matches']['id'];
    $action = $req['matches']['action'];
    $response = Feedback::save($id, $req['form'], $action=='update');
    set_flash_msg($response['status'], $response['msg']);
    $url_map = ['give_success'=>BASE_URL.'review/pending', 'give_error'=>BASE_URL.'review/give/'.$id, 'update_success'=>BASE_URL.'review/given', 'update_error'=>BASE_URL.'review/update/'.$id];
    $url = $url_map[$action.'_'.$response['status']];
    return app\response_302($url);
});