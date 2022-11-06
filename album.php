<?php
require('utils.php');
/*
Date: 8/31/2022

Album of Cards.
Displays all our cards by type.

Feature:
-   Delete a card
-   Edit a Card
    -   Both Position Cards and Question Cards.

Convert cards to PNG/JPG: 
-   Remove card border
-   Remove text background color
-   For the entire card use a given SVG for the image background
-   Size: W 10.88" X H 14.8"
*/
// $my_img = imagecreate( 200, 80 );
// $background = imagecolorallocate( $my_img, 0, 0, 255 );
// $text_color = imagecolorallocate( $my_img, 255, 255, 0 );
// $line_color = imagecolorallocate( $my_img, 128, 255, 0 );
// imagestring( $my_img, 4, 30, 25, "thesitewizard.com", $text_color );
// imagesetthickness ( $my_img, 5 );
// imageline( $my_img, 30, 45, 165, 45, $line_colour );

// header( "Content-type: image/png" );
// imagepng( $my_img );
// imagecolordeallocate( $line_color );
// imagecolordeallocate( $text_color );
// imagecolordeallocate( $background );
// imagedestroy( $my_img );


/**
Draws a Position Card data. For the purposes of the Album page.
*/
function DrawPosCard($pos, bool $delete = true, $question_letter = ""){

    //Add a dot at the end of the question_letter.
    if($question_letter != ""){
        $question_letter .= ". ";
    }

    $uid = $pos['uid'];

    echo "
    <div class='container pos-card' id='$uid'>
    ";
    echo "
        <div>$question_letter\"" .$pos['text'] . "\"</div>

        <div>
            <strong>Bonuses:</strong>
    ";

    if(isset($pos['bonuses'])){
        foreach($pos['bonuses'] as $bonus){
            //Place a + sign if the points are positive:
            $sign = $bonus['points'] >= 0 ? '+' : '';
            echo "<span>". GetCategoryName($bonus['id']) . " " . $sign . $bonus['points'] . ", </span>";
        }
    }
    echo "
        </div>

        <div>
            <strong>Smears:</strong>
        ";
    if(isset($pos['smears'])){
        foreach($pos['smears'] as $bonus){
            //Place a + sign if the points are positive:
            $sign = $bonus['points'] >= 0 ? '+' : '';
            echo "<span>". GetCategoryName($bonus['id']) . " " . $sign . $bonus['points'] . ", </span>";
        }
    }
    echo "
        </div>
        ";

    if($delete){
        echo "
        <div>
            <input type='button' value='Delete' class='btn btn-danger short' onclick='DeleteCard($uid, 0)'>
            <a class='btn btn-success short' href='?page=add&uid=$uid'>Edit</a>
        </div>
        ";
    }

    echo "
    </div>
    ";
}

/**
Draw a Question card data. for the purposes of the Album page.
*/
function DrawQuestionCard($question){

    $uid = $question['uid'];
    
    echo "
    <div class='container que-card' id='$uid'>
        <div>\"" .$question['text'] . "\"</div>

        <strong>Answers:</strong>
    ";

    //Array of characters:
    $alpha = range('A', 'Z');
    $i = 0;
    foreach($question['answers'] as $pos){
        DrawPosCard($pos, false, $alpha[$i++]);
    }

    echo "
        <div>
            <input type='button' value='Delete' class='btn btn-danger short' onclick='DeleteCard($uid, 1)'>
            <a class='btn btn-success short' href='?page=add&uid=$uid'>Edit</a>
        </div>
        ";

    echo "
    </div>
    ";
}

?>

<div class='album' id="capture">
    <div class='container'>
        <h2 class="alert alert-primary">Card Album</h2>

        <!--Form for searching -->
        <div class='container search'>
            <form method='get'>
                <div><input type="text" name='page' value='album' hidden></div>

                <div><label for='keyword'>Keyword:</label></div>
                <div><input type='text' name='keyword' value='<?=$keyword?>'></div>

                <div><label for='id_is'>ID is:</label></div>
                <div><input type='number' name='id_is' class='long' value='<?=$id_is?>'></div>

                <div><label for='greater-than-id'>ID Greater Than:</label></div>
                <div><input type='number' name='greater-than-id' class='long' value='<?=$greater_than_id?>'></div>

                <div><label for='category'>Category:</label></div>
                <div>
                    <select class='chosen-select' name='category'>
                        <?=GetCategories($category)?>
                    </select>
                </div>

                <div><label>Card Type:</label></div>
                <div>
                    <input type="radio" name="card_type" id="both" value="Both" checked>
                    <label for="both">Both</label> 
                    <input type="radio" name="card_type" id="position" value="Position" <?php if($card_type == "Position") echo "checked"; ?> >
                    <label for="position">Position</label>
                    <input type="radio" name="card_type" id="question2" value="Question" <?php if($card_type == "Question") echo "checked"; ?>>
                    <label for="question2">Question</label>                
                </div>

                <div><label>Sub Type:</label></div>
                <div>
                    <input type="radio" name="subtype" id="subboth" value="Both" checked>
                    <label for="subboth">Both</label> 
                    <input type="radio" name="subtype" id="bonus" value="Bonus" <?php if($sub_type == "Bonus") echo "checked"; ?> >
                    <label for="bonus">Bonus</label>
                    <input type="radio" name="subtype" id="smear" value="Smear" <?php if($sub_type == "Smear") echo "checked"; ?>>
                    <label for="smear">Smear</label>                
                </div>

                <div>
                    <input type="submit" name='search' value="Search" class='btn btn-info short'>
                    <input type='submit'class="btn btn-warning short" name='search' value='Print' formaction="/cardmaker/print.php">
                    <input type='button' value='Download' class='btn btn-danger' onclick='DownloadAll()'>
                </div>
            </form>
        </div>

        <div class=''>
            
            <h3>Positions:</h3>
            <div class='container totals'>
                <div><strong>Postion Cards Found:</strong> <?=count($positions)?></div>
            </div>
            <div class="d-flex">
                <?php
                foreach($positions as $pos)
                {
                    //DrawPosCard($pos);

                    $uid = $pos['uid'];

                    echo "<div class='album-group pos' id='$uid'>";
                        echo GetPositionCard($pos);
                        echo GetCardButtons($pos, 0);
                    echo "</div>";
                }
                ?>
            </div>

            <h3>Questions:</h3>
            <div class='container totals'>
                <div><strong>Question Cards Found:</strong> <?=count($questions)?></div>
            </div>
            <div class="d-flex">
                <?php
                foreach($questions as $que)
                {
                    //DrawQuestionCard($que);
                    $uid = $que['uid'];

                    echo "<div class='album-group que' id='$uid'>";
                        echo GetQuestionCardFront($que);
                        echo GetQuestionCardBack($que);
                        echo GetCardButtons($que, 1);
                    echo "</div>";
                }
                ?>
            </div>
        </div>

    </div>
</div>