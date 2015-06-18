<?php
	require __DIR__.'/vendor/autoload.php';

	use phpish\app;
	use phpish\template;

    require __DIR__.'/path.config.php';
	require __DIR__.'/conf/conf.php';

    include_once MODELS_DIR . "session.php";
    include_once MODELS_DIR . "util.php";

    function set_flash_msg($type, $msg) {
        Session::set_alert(array('msg'=>$msg, 'type'=>$type));
    }

    function display_flash_msg() {
        $alert_msgs = Session::get_alert();
        if(empty($alert_msgs)) return "";
        $msg_block = "<script type='text/javascript'>$('#session_alert_msgs').empty()</script>";
        foreach($alert_msgs as $alert_msg) {
            $msg_block .= "<div class='alert alert-" . $alert_msg['type'] . "'><span>" . $alert_msg['msg'] . "</span></div>";
        }
        $msg_block .= "<script type='text/javascript'>
            setTimeout(function () {
                    $('#session_alert_msgs').hide();
                }, 10000);
        </script>";
        Session::remove_alert();
        return $msg_block;
    }

    function query_param($req, $name) {
        if (array_key_exists($name, $req['query']))
            return $req['query'][$name];
        return '';
    }

    function meekrodb_setup(){
        include_once MEEKRODB_PATH.'db.class.php';

        $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
        /*$server = $url["host"];
        $username = $url["user"];
        $password = $url["pass"];
        $db = substr($url["path"], 1);*/
        $server = 'localhost';
        $username = 'root';
        $password = '';
        $db = 'qulture';
        
        DB::$user = $username;
        DB::$password = $password;
        DB::$dbName = $db;
        DB::$host = $server;
        DB::$encoding = 'utf8';
        DB::$error_handler = false;
        DB::$throw_exception_on_error = true;
    }

    app\any('.*', function($req) {
        session_start();
        meekrodb_setup();
        try {
            return app\next($req);
        } catch(MeekroDBException $e) {
            set_flash_msg('error', $e->getMessage());
            return app\response_302("/");
        }
    });

    //drop the slash from the end of the URL
    app\get('{path:.*}/$', function($req) {
        $url = $req['matches']['path'];
        if(empty($url))
            return app\next($req);
        return app\response_301($url);
    });

    app\path_macro(['/'], function() {
        require CONTROLLER_DIR . 'app_request_handler.php';
    });

    app\path_macro(['/review[/.*]'], function() {
        require CONTROLLER_DIR . 'review_request_handler.php';
    });

    app\path_macro(['/user/.*'], function() {
        require CONTROLLER_DIR . 'user_request_handler.php';
    });

    app\path_macro(['/feedback[/.*]'], function() {
        require CONTROLLER_DIR . 'feedback_request_handler.php';
    });

    app\path_macro(['/survey[/.*]'], function() {
        require CONTROLLER_DIR . 'survey_request_handler.php';
    });

    app\path_macro(['/org[/.*]'], function() {
        require CONTROLLER_DIR . 'org_request_handler.php';
    });

    app\path_macro(['/team[/.*]'], function() {
        require CONTROLLER_DIR . 'team_request_handler.php';
    });

    app\path_macro(['/auth/.*'], function() {
        require CONTROLLER_DIR . 'auth_request_handler.php';
    });

?>