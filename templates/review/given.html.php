<section class="wrapper style special fade">
    <div class="container">
        <h2>Completed Reviews</h2>
        <?php if(empty($data)) {?>
            <h4>Sorry! Currently, you don't have any completed reviews.</h4>
        <?php } else { ?>
        <div class="table-wrapper">
            <table class="alt">
                <thead>
                    <tr>
                        <th>Reviewee</th>
                        <th>Survey Name</th>
                        <th>Completed On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $review) { ?>
                        <tr>
                            <td>
                                <a href="<?php echo BASE_URL;?>user/<?php echo $review['reviewee'] ?>" target="_blank"><?php echo $review['reviewee_name'] ?></a>
                                <br><div class="small"><?php echo $review['team_name'] ?>, <?php echo $review['org_name'] ?></div>
                            </td>
                            <td><?php echo $review['survey_name'] ?></td>
                            <td><?php echo date( 'jS F Y', strtotime($review['updated'])) ?></td>
                            <td><a href="<?php echo BASE_URL;?>review/update/<?php echo $review['id'] ?>" class="button special">Update</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>
</section>


