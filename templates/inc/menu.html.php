<header id="header">
    <h1 id="logo"><a href="<?php echo BASE_URL;?>"><img src="<?php echo BASE_URL;?>static/images/logo.png" alt="Qulture.Rocks"/></a></h1>
    <nav id="nav">
        <ul>
            <li>
                <a href="#">Survey</a>
                <ul>
                    <li><a href="<?php echo BASE_URL;?>survey/create"><i class="icon fa-plus">&nbsp;</i> Create</a></li>
                    <li><a href="<?php echo BASE_URL;?>survey"><i class="icon fa-list-alt">&nbsp;</i> View</a></li>
                </ul>
            </li>
            <?php if(Session::is_inactive()) {?>
                <li><a href="<?php echo BASE_URL;?>auth/login" class="button special">Login</a></li>
            <?php } else { ?>
                <li>
                    <a href="#">Reviews</a>
                    <ul>
                        <li><a href="<?php echo BASE_URL;?>review/pending"><i class="icon fa-warning">&nbsp;</i> Pending</a></li>
                        <li><a href="<?php echo BASE_URL;?>review/received"><i class="icon fa-download">&nbsp;</i> Received</a></li>
                        <li><a href="<?php echo BASE_URL;?>review/given"><i class="icon fa-check">&nbsp;</i> Given</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="button special"><?php echo Session::name()?> <i class="icon fa-caret-down">&nbsp;</i></a>
                    <ul>
                        <li><a href="<?php echo BASE_URL;?>user/<?php echo Session::username()?>"><i class="icon fa-user">&nbsp;</i> View Profile</a></li>
                        <li><a href="<?php echo BASE_URL;?>user/update-profile"><i class="icon fa-edit">&nbsp;</i> Update Profile</a></li>
                        <li><a href="<?php echo BASE_URL;?>org"><i class="icon fa-group">&nbsp;</i> My Orgs</a></li>
                        <li><a href='<?php echo BASE_URL;?>user/change-password'><i class="icon fa-lock">&nbsp;</i> Change Password</a></li>
                        <li><a href="<?php echo BASE_URL;?>auth/logout"><i class="icon fa-power-off">&nbsp;</i> Logout</a></li>
                    </ul>
                </li>
            <?php } ?>
        </ul>
    </nav>
</header>