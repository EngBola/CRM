<nav class="navbar navbar-expand-lg navbar-light ">
  <div class="container">
    <a class="navbar-brand" href="index.php"><img src="./layout/images/crm2.png" width="70px"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item ">
          <a class="nav-link" href="index.php"><?php echo lang('HOME'); ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="categories.php"><?php echo lang('CATEGORIES'); ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="items.php"><?php echo lang('ITEM'); ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="member.php"><?php echo lang('MEMBER'); ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="comments.php"><?php echo  lang('COMMENTS'); ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo lang('STATIC'); ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo lang('LOGS'); ?></a>
        </li>
      </ul>​

      <ul class="navbar-nav ml-1">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle " href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo 'Welcome ' . $_SESSION['UserName']; ?>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="member.php?do=Edit&userid=<?php echo sha1($_SESSION['ID']); ?>"><?php echo lang('MYPROFILE'); ?></a>
            <a class="dropdown-item" href="#"><?php echo lang('SETTINGS'); ?></a>
            <a class="dropdown-item" href="logout.php"><?php echo lang('LOGOUT'); ?></a>
          </div>
        </li>
      </ul>
      <img src="./layout/images/crm.png" class=" headprof rounded-circle" alt="...">
    </div>

  </div>


</nav>