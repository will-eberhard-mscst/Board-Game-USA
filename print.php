<?php
require('utils.php');
/*
Date: 9/17/2022

Page for displaying the cards for printing.
Consider making this a seperate White webpage with no Nav or Footer so that this page can only be used for printing.
Print will be a button on the Album page instead that prints only the cards from the search query.
Move all the CSS to another folder.

See flex container:
https://getbootstrap.com/docs/4.0/utilities/flex/

See card layout:
https://getbootstrap.com/docs/4.0/components/card/#content-types
*/
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Card Maker</title>

        <link rel="stylesheet" href="../utils/bootstrap-5.2.0-dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/cardmaker.css" />
    </head>
    <body>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="../utils/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"></script>
    
        <div class="print">
            <div class="">
                <div class="d-flex">        
                    
                    <?php
                    foreach($positions as $pos)
                    {
                        echo GetPositionCard($pos);
                    }
                    ?>

                </div>
            </div>
        </div>
        
    </body>
</html>