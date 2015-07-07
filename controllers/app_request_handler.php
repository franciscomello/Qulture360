<?php

use phpish\app;
use phpish\template;

include_once MODELS_DIR . 'review.php';
include_once MODELS_DIR . 'feedback.php';

app\get("/", function($req) {
	if(Session::is_inactive()){
    $data = [];
    return template\compose("index.html", compact('data'), "layout.html");		
	}else{
		$data = Review::pending();
		if (count($data)>0){
    	return template\compose("review/pending.html", compact('data'), "layout.html");				
		}else{
			return app\response_302(BASE_URL.'survey/create');
		}
	}
});
