
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" 
    crossorigin="anonymous">
</head>
<body> 
    <?php 
        $bg_color = '#dcdcdc';
        echo "<body style='background-color: $bg_color;'>"; 
    ?> 
    
    <style> 
        h2 {
            font-size: 0.5cm;
            color: black;
        }
    </style>

</br>


<nav class="navbar navbar-expand-lg position-fixed sticky-top bg-body-tertiary" data-bs-theme="dark" style="width: 100%;">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Home</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="true" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="gatehouse.php">Gatehouse</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="loops.php">Loops</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="lists.php">Lists</a>
        </li>
        <li class="nav-item dropdown" data-bs-theme="dark">
          <a class="nav-link active dropdown-toggle" href="nonamazon.php" role="button" data-bs-toggle="dropdown" aria-expanded="true">Non Amazon</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="zeus.php">Zeus</a></li>
            <li><a class="dropdown-item" href="contactinfo.php">DHL</a></li>
            <li><a class="dropdown-item" href="amazon.php">Stobart</a></li>
            <li><a class="dropdown-item" href="amazon.php">Bluapple</a></li>
            <li><a class="dropdown-item" href="amazon.php">Royal Mail</a></li>
          
          </ul>
        </li>
        <li class="nav-item dropdown" data-bs-theme="dark">
          <a class="nav-link active dropdown-toggle" href="contactinfo.php" role="button" data-bs-toggle="dropdown" aria-expanded="true">Contact Info</a>
          <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="contactinfozeus.php">Zeus</a></li>
            <li><a class="dropdown-item" href="contactinfo.php">DHL</a></li>
            <li><a class="dropdown-item" href="amazon.php">Stobart</a></li>
            <li><a class="dropdown-item" href="amazon.php">Bluapple</a></li>
            <li><a class="dropdown-item" href="amazon.php">Royal Mail</a></li>
          </ul>
        </li>

        
      </ul>
      <div id="searchResults"></div>

      <form class="d-flex" id="searchForm">
    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" id="searchInput">
    <button class="btn btn-outline-success" type="submit" id="searchButton">Search</button>
</form>




      </div>
    </div>
  </div>
</nav>
     






