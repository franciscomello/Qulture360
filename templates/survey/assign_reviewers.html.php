<?php
$post_url = BASE_URL."survey/".$data['survey_id']."/add-reviewers";
$action = 'Assign';
$current_assignment = [];
include_once TEMPLATE_PATH . "survey/inc/manage_reviewers.html.php";