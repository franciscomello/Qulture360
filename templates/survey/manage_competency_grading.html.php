<?php
include_once TEMPLATE_PATH. "inc/html_helper.php";

$competency_data = $data['competency_data'];
$base_data = $competency_data['base_data'];
$custom_grading = $competency_data['custom_grading'];
$default_grading = $data['default_grading'];

$competency_id = $base_data['id'];
$competency_name = $base_data['name'];

$using_default_grading = true;
if (count($custom_grading)>0){
    $using_default_grading = false;
}

?>

<section class="wrapper style special fade">
    <div class="container">
        <form action="<?php echo BASE_URL;?>survey/competency/<?php echo $competency_id;?>/edit" id="theForm" method="post">
            <h2>Edit the Grading Texts of</h2>
            <h3 style="text-align: center"><b><?php echo $competency_name?></b></h3>

            <p style="text-align: center">You can use the default options or set your own text</p>

            <div class="row uniform 50%">
                
                <div class="3u 12u$(medium) form-label"><label for="name">Use Default Options</label></div>
                
                <div class="9u$ 12u$(medium)">
                    <div class="12u$ 12u$(medium)">
                        <input type="checkbox" id="enable_default_grading" name="enable_default_grading" value="<?php echo $competency_id; ?>" <?php if($using_default_grading) {echo 'checked="checked"';}?> autocomplete="off">
                        <label class="checkbox-label" for="enable_default_grading">Use Default</label>
                    </div>
                </div>

                <div class="3u 12u$(medium) form-label"><label for="grade_0_field">Grade 0</label></div>
                <div class="9u$ 12u$(medium)"><input type="text" class="input_custom_grade" <?php if($using_default_grading) {echo 'disabled="disabled" value=""';}else{ echo 'value="'.$custom_grading[0]['grade_text'].'"'; }?> name="grade_0_field" id="grade_0_field" placeholder="<?php echo $default_grading['0']; ?>" required minlength="2"></div>

                <div class="3u 12u$(medium) form-label"><label for="grade_1_field">Grade 1</label></div>
                <div class="9u$ 12u$(medium)"><input type="text" class="input_custom_grade" <?php if($using_default_grading) {echo 'disabled="disabled" value=""';}else{ echo 'value="'.$custom_grading[1]['grade_text'].'"'; }?> name="grade_1_field" id="grade_1_field" placeholder="<?php echo $default_grading['1']; ?>" required minlength="2"></div>

                <div class="3u 12u$(medium) form-label"><label for="grade_2_field">Grade 2</label></div>
                <div class="9u$ 12u$(medium)"><input type="text" class="input_custom_grade" <?php if($using_default_grading) {echo 'disabled="disabled" value=""';}else{ echo 'value="'.$custom_grading[2]['grade_text'].'"'; }?> name="grade_2_field" id="grade_2_field" placeholder="<?php echo $default_grading['2']; ?>" required minlength="2"></div>

                <div class="3u 12u$(medium) form-label"><label for="grade_3_field">Grade 3</label></div>
                <div class="9u$ 12u$(medium)"><input type="text" class="input_custom_grade" <?php if($using_default_grading) {echo 'disabled="disabled" value=""';}else{ echo 'value="'.$custom_grading[3]['grade_text'].'"'; }?> name="grade_3_field" id="grade_3_field" placeholder="<?php echo $default_grading['3']; ?>" required minlength="2"></div>

                <div class="3u 12u$(medium) form-label"><label for="grade_4_field">Grade 4</label></div>
                <div class="9u$ 12u$(medium)"><input type="text" class="input_custom_grade" <?php if($using_default_grading) {echo 'disabled="disabled" value=""';}else{ echo 'value="'.$custom_grading[4]['grade_text'].'"'; }?> name="grade_4_field" id="grade_4_field" placeholder="<?php echo $default_grading['4']; ?>s" required minlength="2"></div>

                <div class="3u 12u$(medium) form-label"><label for="grade_5_field">Grade 5</label></div>
                <div class="9u$ 12u$(medium)"><input type="text" class="input_custom_grade" <?php if($using_default_grading) {echo 'disabled="disabled" value=""';}else{ echo 'value="'.$custom_grading[5]['grade_text'].'"'; }?> name="grade_5_field" id="grade_5_field" placeholder="<?php echo $default_grading['5']; ?>" required minlength="2"></div>
         

                <div class="3u 12u$(medium)"></div>
                <div class="3u 12u$(medium)"><a href="<?php echo BASE_URL;?>survey/create" class="button cancel_button">Cancel</a></div>
                <div class="3u 12u$(medium)"><input type="submit" value="Update" class="button special" /></div>
                <div class="3u$ 12u$(medium)"></div>
            </div>
        </form>
    </div>
</section>
<script src="<?php echo BASE_URL;?>static/js/competency_grading_manager.js"></script>


