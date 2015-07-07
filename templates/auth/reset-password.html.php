<section class="wrapper style special fade">
    <div class="container 50%">
        <?php if (!empty($data) && array_key_exists('user_details', $data)) { ?>
            <form action="<?php echo BASE_URL;?>auth/reset-password" id="theForm" method="post" class="form-signin" align="left">
                <h2>Reset Password</h2>
                <input name="email" type="hidden" value="<?php echo $data['user_details']['email']; ?>">
                <input name="activation_token" type="hidden" value="<?php echo $data['user_details']['activation_token']; ?>">

                <div class="row uniform 50%">
                    <div class="6u 12u$(medium) form-label"><label for="new_password">Password<sup>*</sup></label></div>
                    <div class="6u$ 12u$(medium)"><input type="password" name="password" id="new_password" placeholder="Password" required minlength="6" autocomplete="off"></div>

                    <div class="6u 12u$(medium) form-label"><label for="confirmation-password">Confirm Password<sup>*</sup></label></div>
                    <div class="6u$ 12u$(medium)"><input type="password" name="confirmation-password" id="confirmation-password" placeholder="Confirm Password" required minlength="6"></div>

                    <div class="6u 12u$(medium)"></div>
                    <div class="6u$ 12u$(medium)"><input type="submit" value="Continue" class="fit special" /></div>
                </div>
            </form>
            <?php include_once TEMPLATE_PATH. "inc/jquery_validator.php"; ?>
        <?php } else { ?>
            <h2>Please <a href="<?php echo BASE_URL;?>auth/login">login</a> first.</h2>
        <?php } ?>
    </div>
</section>


