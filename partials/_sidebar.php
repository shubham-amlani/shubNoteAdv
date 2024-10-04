<?php
echo '<div class="top-nav d-flex justify-content-center fixed-top align-items-center flex-column">
<h2 class="highlight mb-0 mt-2">shubNote</h2>
<span class="mb-2">a Shubham Amlani Production</span>
</div>
<nav class="sidebar-c d-flex align-items-start flex-column justify-content-evenly gap-5 px-4" id="sidebar-c">
<h2 class="logo highlight my-5">shubNote</h2>
<ul class="nav flex-column gap-2">
    <li class="nav-item">
        <a class="nav-link '.(checkPage('home.php')?'active':'').'" href="home.php"><i class="fas fa-home"></i> Home</a>
    </li>
    <li class="nav-item">
        <a class="nav-link '.(checkPage('explore.php')?'active':'').'" href="explore.php"><i class="fas fa-compass"></i> Explore</a>
    </li>
    <li class="nav-item">
        <a class="nav-link '.(checkPage('search.php')?'active':'').'" href="search.php"><i class="fas fa-search"></i> Search</a>
    </li>
    <li class="nav-item">
        <a class="nav-link '.(checkPage('about.php')?'active':'').'" href="about.php"><i class="fas fa-info-circle"></i> About</a>
    </li>
    <li class="nav-item">
        <a class="nav-link '.(checkPage('myaccount.php')?'active':'').'" href="myaccount.php"><i class="fas fa-user"></i> Profile</a>
    </li>
</ul>
<span class="mt-5">a Shubham Amlani Production</span>
</nav>';
?>