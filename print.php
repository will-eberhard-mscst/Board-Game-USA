<?php
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

<div class="print">
    <div class="container">
        <div class="d-flex">

            <div class="card">
                <div class="card-body">
                    <p class="card-text"><span>"The other day an officer went to Little Caesars and when he arrived home with his pizza, he found "F the Police" inscribed on the inside of the box! The amount of disrespect our men in blue are facing these days is ridiculous!"</span></p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <p class="card-text"><span>"I will ban all off-shore drilling."</span></p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <p class="card-text"><span>"Our first priority needs to be solving the climate change crisis. By implementing a Carbon Tax, we can both dissuade environmentally unfriendly purchases, and fund efforts to switch America to renewable energies."</span></p>
                </div>
            </div>

        </div>
    </div>
</div>