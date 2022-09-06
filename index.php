<!DOCTYPE html>
<html lang="en">
<?php
require('classes/card.php');
?>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Card Maker</title>

    <link rel="stylesheet" href="../utils/bootstrap-5.2.0-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../utils/chosen/chosen.min.css">
    <link rel="stylesheet" type="text/css" href="../utils/datatables/datatables.min.css"/>
    <link rel="stylesheet" href="css/cardmaker.css" />
</head>
<body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../utils/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../utils/chosen/chosen.jquery.min.js"></script>
    <script type="text/javascript" src="../utils/datatables/datatables.min.js"></script>
    
    <header>
        <nav class="navbar navbar-expand-sm navbar-toggleable-sm navbar-light bg-dark border-bottom box-shadow mb-3">
            <div class="container">
                <a class="navbar-brand text-light" asp-area="" asp-controller="Home" asp-action="Index">Card Maker</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse collapse d-sm-inline-flex flex-sm-row-reverse">
                    <ul class="navbar-nav flex-grow-1">
                        <li class="nav-item">
                            <a class="nav-link text-light" href='?page=add'>Create</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href='?page=album'>Album</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href='?page=stats'>Stats</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div class="container">
        <main role="main" class="pb-3">
            <?php 
            $page = $_GET['page'];
            include($page . '.php');
            ?>
        </main>
    </div>

    <footer class="border-top footer text-muted">
        <div class="container">
            &copy; 2022 - Card Maker 
        </div>
    </footer>

    
    <script src="js/site.js"></script>

</body>
</html>
