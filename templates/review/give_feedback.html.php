<?php
include_once TEMPLATE_PATH. "inc/html_helper.php";
$reviewee_name = $data['reviewee_name'];
?>

<section class="wrapper style special fade">
    <div class="container">
        <h2><?php echo $data['title']?> Feedback for <?php echo $reviewee_name?></h2>
        <form action="<?php echo $data['post_url']?>" id="theForm" method="post">
            <div class="row uniform 50%">
                <?php foreach($data['competencies'] as $competency) {
                    $competency_id = $competency['competency_id'];
                    $competency_name = $competency['name'];
                    $rating_id = $competency_id.'_rating';
                    $confidence_id = $competency_id.'_good';
                    $effectiveness_id = $competency_id.'_bad';

                    $default_instructions_good = $data['default_competencies_instructions']['good'];
                    $default_instructions_bad = $data['default_competencies_instructions']['bad'];

                    if ($competency['instructions_for_good']!= '' OR $competency['instructions_for_bad']!= ''){
                        $default_instructions_good = $competency['instructions_for_good'];
                        $default_instructions_bad = $competency['instructions_for_bad'];
                    }

                    $default_instructions_good = str_replace('__competency_name__', $competency['name'], $default_instructions_good);
                    $default_instructions_good = str_replace('__reviewee_name__', $reviewee_name, $default_instructions_good);

                    $default_instructions_bad = str_replace('__competency_name__', $competency['name'], $default_instructions_bad);
                    $default_instructions_bad = str_replace('__reviewee_name__', $reviewee_name, $default_instructions_bad);


                ?>
                    <div class="12u$ 12u$(medium)"><b><?php echo $competency_name . "</b>: ". $competency['description']?></div>

                    <div class="3u 12u$(medium) form-label"><label for="<?php echo $rating_id?>"><?php echo $competency_name ?><sup>*</sup></label></div>
                    <div class="9u$ 12u$(medium)">
                        <div class="select-wrapper">
                            <select name="<?php echo $rating_id?>" id="<?php echo $rating_id?>">
                                <?php
                                    $custom_ratings = $competency['full_data']['custom_grading'];
                                    if (count($custom_ratings)<6){
                                       $ratings_for_options = $data['ratings'];
                                    }else{
                                        $ratings_for_options = array();
                                        foreach ($custom_ratings as $key => $value) {
                                          $ratings_for_options[$value['grade_position']] = $value['grade_text']; 
                                        }
                                    }
                                ?>
                                <?php echo build_options_from_map($ratings_for_options, $competency['rating']); ?>
                            </select>
                        </div>
                    </div>

                    <div class="3u 12u$(medium) form-label"><label for="<?php echo $confidence_id?>">Positive examples</label></div>
                    <div class="9u$ 12u$(medium)">
                        <textarea name="<?php echo $confidence_id?>" id="<?php echo $confidence_id?>" placeholder="Highlight at least one specific instances with regards to <?php echo $competency_name ?>, that <?php echo $reviewee_name?> is doing well..."><?php echo $competency['good'] ?></textarea>                    </div>

                    <div class="3u 12u$(medium) form-label"><label for="<?php echo $effectiveness_id?>">Needs improvement</label></div>
                    <div class="9u$ 12u$(medium)">
                        <textarea name="<?php echo $effectiveness_id?>" id="<?php echo $effectiveness_id?>" placeholder="Highlight at least one specific instances with regards to <?php echo $competency_name ?>, along with some suggestions, that could help <?php echo $reviewee_name?> improve..."><?php echo $competency['bad'] ?></textarea>                    </div>

                    <div class="12u$ 12u$(medium)"><hr></div>

                <?php } ?>

                <div class="3u 12u$(medium)"></div>
                <div class="3u 12u$(medium)"><a href="<?php echo $data['cancel_url']?>" class="button cancel_button">Cancel</a></div>
                <div class="3u 12u$(medium)"><input type="submit" value="Save" class="button special" /></div>
                <div class="3u$ 12u$(medium)"></div>
            </div>
        </form>
        <?php include_once TEMPLATE_PATH. "inc/jquery_validator.php"; ?>
    </div>
</section>


