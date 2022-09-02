<?php
/*
DATE: 9/2/2022

-   uid will be a parameter. Without a UID, this page is useless.
-   All the current values will be set as the default values.
-   On submit, delete all current positions, smears, and bonuses and submit the new card as is.
*/


$isEdit = isset($_GET['uid']);

?>

<div class='create'>
    <div class='container'>
        <h2 class="alert alert-primary">Edit Card</h2>

        <div class='alert alert-secondary'>
            <h5>NOTE:</h5>
            <ul>
                <li>If you want to delete a Bonus or a Smear, set the Points to 0.</li>
            </ul>
        </div>

        <form method='post'>

            <!--
                Hidden Card Type value. 
                Set the value as either 'Position' or 'Question' based on the current card type.
            -->
            <div>       
                <input type="text" name="card_type" value="Position" hidden>
            </div>

            <div id='question_text' hidden>
                <div><label for='question_text'>Question Text</label></div>
                <div><textarea name='question_text'></textarea></div>
            </div>

            <div id='positions'>
                <?php
                /*
                The number of positions will be based what the card already has.
                */
                ?>
            </div>


            <div><input type="submit" name='submit' value="Submit" class='btn btn-success long'></div>

        </form>
    </div>
</div>