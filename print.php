<?php
require('utils.php');
/*
Date: 9/17/2022

Page for displaying the cards for printing.
This is a seperate White webpage with no Nav or Footer so that this page can only be used for printing.
Print is a button on the Album page instead that prints only the cards from the search query.
Move all the CSS to another folder.

What needs to be done:
-   We still need to draw Question cards.
    -   Question cards are landscapes
    -   Each Question card will need 2 cards drawn
        -   one for the front
        -   one for the back
-   Smear image needs to be an SVG
-   Welfare image needs a new SVG file


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

                    <div class="card print-qcard">
                        <div class="card-header">
                            "As President, you can either try to work across the aisle and move legislation or can fight partisan battles in the media, rake in the fundraising dollars, and become a major player in your party's politics. Which are you? A deal maker, or a partisan fighter?"
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">A. "I will always fight for the American people, but that doesn't mean you can't work together to get things done."</li>
                            <li class="list-group-item">B. "Politics has gotten so divisive that we need to focus on getting things done. Americans are frustrated by the politics and want progress. The only way to get progress is by working together."</li>
                            <li class="list-group-item">C. "Neither. What we need is a disrupter. I will do things that both sides hate in order to force them to work together."</li>
                        </ul>
                    </div>

                    <div class="card print-qcard qcard-back">
                        
                        <div class="card-header">
                            <div class="child">Answers</div>
                        </div>
                        
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                A.
                                <img class="card-img answer-img" src="/cardmaker/images/svg/cat_nat_svg.svg" alt="Answer"> +4
                                <img class="card-img answer-img" src="/cardmaker/images/svg/cat_nat_svg.svg" alt="Answer"> +4
                                <img class="card-img answer-img" src="/cardmaker/images/svg/cat_nat_svg.svg" alt="Answer"> +4
                                <img class="card-img answer-img" src="/cardmaker/images/svg/cat_nat_svg.svg" alt="Answer"> +4
                            </li>
                            <li class="list-group-item">B. <img class="card-img answer-img" src="/cardmaker/images/svg/cat_nat_svg.svg" alt="Answer"></li>
                            <li class="list-group-item">C. <img class="card-img answer-img" src="/cardmaker/images/svg/cat_nat_svg.svg" alt="Answer"></li>
                        </ul>
                    </div>    

                </div>
            </div>
        </div>
        
    </body>
</html>