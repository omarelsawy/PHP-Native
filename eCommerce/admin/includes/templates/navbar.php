<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="dashboard.php"><?php echo lang('home_admin')?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="app-nav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="categories.php"><?php echo lang('categories') ?></a>
            </li>
            <li>
                <a class="nav-link" href="items.php"><?php echo lang('Items') ?></a>
            </li>
            <li>
                <a class="nav-link" href="members.php"><?php echo lang('Members') ?></a>
            </li>
            <li>
                <a class="nav-link" href="comments.php"><?php echo lang('Comments') ?></a>
            </li>
            <li class="nav-item">

            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    omar
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/index.php">Shop</a>
                    <a class="dropdown-item" href="members.php?do=edit&userid=<?php echo $_SESSION['id']?>">Edit profile</a>
                    <a class="dropdown-item" href="#">Settings</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            </li>
            <li class="nav-item">

            </li>
        </ul>
    </div>
</nav>