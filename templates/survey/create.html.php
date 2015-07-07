<?php
include_once TEMPLATE_PATH. "inc/html_helper.php";

$org_wise_teams = $data['teams'];
$org_ids_raw = array_keys($org_wise_teams);
$first_org_id = current($org_ids_raw);
$org_ids = build_options_from($org_ids_raw);
$teams = build_options_from_map($org_wise_teams[$first_org_id]);
$competencies = $data['competencies'];
?>

<section class="wrapper style special fade">
    <div class="container">
        <form action="<?php echo BASE_URL;?>survey/create" id="theForm" method="post">
            <h2>Create a Survey</h2>

            <p style="text-align: center">Please make sure you've read <a href="<?php echo BASE_URL;?>#make_it_work" target="_blank">Make 360 Feedback Work</a>, before rolling this out!</p>

            <div class="row uniform 50%">
                <div class="3u 12u$(medium) form-label"><label for="name">Survey Name<sup>*</sup></label></div>
                <div class="9u$ 12u$(medium)"><input type="text" name="name" id="name" placeholder="Survey Name (Ex: 1st Quarter Review)" required minlength="2"></div>

                <div class="3u 12u$(medium) form-label"><label for="org_id">Org. Name<sup>*</sup></label></div>
                <div class="9u$ 12u$(medium)">
                    <div class="select-wrapper">
                        <select name="org_id" id="org_id">
                            <?php echo $org_ids; ?>
                        </select>
                    </div>
                </div>

                <div class="3u 12u$(medium) form-label"><label for="team_id">Team Name<sup>*</sup></label></div>
                <div class="9u$ 12u$(medium)">
                    <div class="select-wrapper">
                        <select name="team_id" id="team_id">
                            <?php echo $teams; ?>
                        </select>
                    </div>
                </div>

                <div class="3u 12u$(medium) form-label"><label>Competencies<sup>*</sup></label></div>
                <div class="9u$ 12u$(medium)" id="competencies-list">
                    <?php foreach($competencies as $competency) {
                        $unique_id = "competency_".$competency['id'];
                        ?>
                        <div class="12u$ 12u$(medium)">
                            <input type="checkbox" id="<?php echo $unique_id; ?>" name="competencies[]" value="<?php echo $competency['id']; ?>" checked="">
                            <label class="checkbox-label" for="<?php echo $unique_id; ?>"><b><?php echo $competency['name']; ?></b><br/><?php echo $competency['description']; ?></label>
                        </div>
                    <?php } ?>
                </div>
                
                <div class="3u 12u$(medium) form-label"><label>Add Custom competency</label></div>
                <div class="9u$ 12u$(medium)">
                    <input type="text" name="new-name" id="new-name" placeholder="Name" minlength="2">
                </div>
                 <div class="3u 12u$(medium) form-label"><label></label></div>
                <div class="9u$ 12u$(medium)">
                    <textarea name="new-description" id="new-description" placeholder="Description"></textarea>
                </div>

                <div class="add-new" style="float: right;"><input type="button" value="Add" class="button special" onclick="addCustomCompetency()"></div>

                <div class="3u 12u$(medium) form-label" style="clear: both;"><label><sup>*</sup></label></div>
                <div class="9u$ 12u$(medium)">
                    <input type="checkbox" id="aggregated_score" name="aggregated_score" value="yes" checked>
                    <label class="checkbox-label" for="aggregated_score">Display only aggregated score per competency to Reviewee</label>
                </div>

                <div class="3u 12u$(medium)"></div>
                <div class="3u 12u$(medium)"><a href="<?php echo BASE_URL;?>" class="button cancel_button">Cancel</a></div>
                <div class="3u 12u$(medium)"><input type="submit" value="Create" class="button special" /></div>
                <div class="3u$ 12u$(medium)"></div>
            </div>
        </form>
        <?php include_once TEMPLATE_PATH. "inc/jquery_validator.php"; ?>
    </div>
</section>

<script type="text/javascript">
    var org_teams = <?php echo json_encode($org_wise_teams)?>;
    $("#org_id").change(function(){
        var teams = org_teams[$(this).val()];
        $("#team_id").empty();
        $.each(teams, function(key, value){
            $("#team_id").append($('<option/>', {
                value: key,
                text : value
            }));
        });
    });

    function addCustomCompetency() {
        var competencyName = $('#new-name').val();
        var competencyDescription = $('#new-description').val();
        if(competencyName != '') {
            $.ajax({
                type: 'POST',
                url: base_url+'survey/add-new',
                data: 'name='+competencyName+'&description='+competencyDescription,
                success: function(data) {
                    location.reload();
                }
            });
        }
    }
</script>


