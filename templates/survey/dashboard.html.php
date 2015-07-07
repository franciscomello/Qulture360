<section class="wrapper style special fade">
    <div class="container">
        <h2>My Surveys</h2>
        <?php if(empty($data)) {?>
            <h4>Looks like you've not created any surveys so far. <a href="<?php echo BASE_URL;?>survey/create">Get started...</a></h4>
        <?php } else { ?>
            <div class="table-wrapper">
                <table class="alt">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Org</th>
                            <th>Team</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data as $survey) { ?>
                            <tr>
                                <td><?php echo $survey['name'] ?></td>
                                <td><?php echo $survey['org_name'] ?></td>
                                <td><?php echo $survey['team_name'] ?></td>
                                <td><?php echo date( 'jS F Y', strtotime($survey['created'])) ?></td>
                                <td>
                                    <a href="<?php echo BASE_URL;?>survey/<?php echo $survey['id'] ?>/overview" alt="View" title="View"><i class="icon fa-dashboard">&nbsp;</i></a>
                                    <a href="<?php echo BASE_URL;?>survey/<?php echo $survey['id'] ?>/edit-reviewers" alt="Edit Reviewers" title="Edit Reviewers"><i class="icon fa-users">&nbsp;</i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</section>


