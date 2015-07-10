<?php
include_once TEMPLATE_PATH. "inc/html_helper.php";

$competencies = $data['competencies'];
$default_instructions = $data['default_instructions'];
$survey_info = $data['survey_info'];

?>

<section class="wrapper style special fade">
    <div class="container">
        <h2>Edit the Competencies Instructions Texts from <?php echo $survey_info['name'];?></h2>
        <p>You can use <b style="font-weight: bold">__reviewee_name__</b> as a placeholder and we'll put the proper Reviewee Name there later</p>
        <?php
        foreach ($competencies as $a_competency) {
            $using_customized_instructions = false;
            if ($a_competency['instructions_for_good']!='' OR $a_competency['instructions_for_good']!=''){
                $using_customized_instructions = true;
            }
            $default_good = str_replace('__competency_name__', $a_competency['name'], $default_instructions['good']);
            $default_bad = str_replace('__competency_name__', $a_competency['name'], $default_instructions['bad']);
        ?>
        <form action="<?php echo BASE_URL;?>survey/<?php echo $survey_info['id'];?>/competency/<?php echo $a_competency['id'];?>/edit" method="POST">
            <div class="row uniform 50%">
                <div class="3u 12u$(medium) form-label"><label for="name" style="font-weight:bold;"><?php echo $a_competency['name'];?></label></div>                
                <div class="9u$ 12u$(medium)">
                    <div class="12u$ 12u$(medium)">
                        <input type="checkbox" id="using_customized_instructions<?php echo $a_competency['id']; ?>" class="checkbox_use_customized_instructions" name="using_customized_instructions<?php echo $a_competency['id']; ?>" value="<?php echo $a_competency['id']; ?>" <?php if($using_customized_instructions) {echo 'checked="checked"';}?> autocomplete="off">
                        <label class="checkbox-label" for="using_customized_instructions<?php echo $a_competency['id']; ?>">Customize Instructions</label>
                    </div>
                </div>
                
                <div class="3u 12u$(medium) form-label"><label for="instructions_for_good">Positive examples instruction</label></div>
                <div class="9u$ 12u$(medium)">
                    <input type="text" class="input_custom_instruction" <?php if(!$using_customized_instructions) {echo 'disabled="disabled" value=""';}else{ echo 'value="'.$a_competency['instructions_for_good'].'"'; }?> name="instructions_good" id="instructions_good" placeholder="<?php echo $default_good; ?>" data-default-placeholder="<?php echo $default_good; ?>" required minlength="2">
                </div>

                <div class="3u 12u$(medium) form-label"><label for="instructions_for_bad">Needs improvement instruction</label></div>
                <div class="9u$ 12u$(medium)">
                    <input type="text" class="input_custom_instruction" <?php if(!$using_customized_instructions) {echo 'disabled="disabled" value=""';}else{ echo 'value="'.$a_competency['instructions_for_bad'].'"'; }?> name="instructions_bad" id="instructions_bad" placeholder="<?php echo $default_bad; ?>" data-default-placeholder="<?php echo $default_bad; ?>" required minlength="2">
                </div>
                <div class="3u 12u$(medium)"></div>
                <div class="3u 12u$(medium)"><input type="submit" value="Update" class="button special" /></div>
                <div class="3u$ 12u$(medium)"></div>
            </div>
        </form>
        <?php
        }
        ?>
    <div class="row uniform 50%" style="text-align: left;">
        <div class="3u 12u$(medium)"></div>
        <div class="3u 12u$(medium)"><a href="<?php echo BASE_URL;?>survey" class="button">Go Back to Surveys</a></div>
    </div>
    </div>
</section>
<script src="<?php echo BASE_URL;?>static/js/competency_instructions_manager.js"></script>


