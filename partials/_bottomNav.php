<?php
echo '<div class="bottom-nav d-flex justify-content-center fixed-bottom align-items-center">
<ul class="nav justify-content-evenly align-items-center w-100 h-100">
    <li class="nav-item">
        <a class="nav-link '.(checkPage('home.php')?'active':'').'" href="home.php"><i class="fas fa-home"></i></a>
    </li>
    <li class="nav-item">
        <a class="nav-link '.(checkPage('explore.php')?'active':'').'" href="explore.php"><i class="fas fa-compass"></i></a>
    </li>
    <li class="nav-item">
        <a class="nav-link '.(checkPage('search.php')?'active':'').'" href="search.php"><i class="fas fa-search"></i></a>
    </li>
    <li class="nav-item">
        <a class="nav-link '.(checkPage('about.php')?'active':'').'" href="about.php"><i class="fas fa-info-circle"></i></a>
    </li>
    <li class="nav-item">
        <a class="nav-link '.(checkPage('myaccount.php')?'active':'').'" href="myaccount.php"><i class="fas fa-user"></i></a>
    </li>
</ul>
</div>';
?>