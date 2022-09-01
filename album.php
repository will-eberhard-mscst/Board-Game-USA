<?php
/*
Date: 8/31/2022

Album of Cards.
Displays all our cards by type.

Feature:
-   Delete a card
-   Edit a Card
    -   Both Position Cards and Question Cards.
*/


//Read the JSON file
$json_filepath = "data/cards.json";
$json = file_get_contents($json_filepath);

// Decode the JSON file
$json_data = json_decode($json, true);

// Display data
//echo "<pre><code>" . print_r($json_data) . "</code></pre>";

$lang = $json_data['eng'];
$categories = $lang['categories'];

//echo "<pre><code>" . print_r($categories) . "</code></pre>";


/*
Returns the Name of the Category given the ID.
*/
function GetCategoryName($id){
    global $categories;

    foreach($categories as $cat){
        if($cat['id'] == $id){
            return $cat['name'];
        }
    }

    return $id;
}

/*
Draws a Position Card.
*/
function DrawPosCard($pos, bool $delete = true, $question_letter = ""){

    //Add a dot at the end of the question_letter.
    if($question_letter != ""){
        $question_letter .= ". ";
    }

    echo "
    <div class='container pos-card'>
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
            <input type='button' value='Delete' class='btn btn-danger short' onclick=''>
            <input type='button' value='Edit' class='btn btn-success short' onclick=''>
        </div>
        ";
    }

    echo "
    </div>
    ";
}

/*
Draw a Question card.
*/
function DrawQuestionCard($question){
    
    echo "
    <div class='container que-card'>
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
            <input type='button' value='Delete' class='btn btn-danger short' onclick=''>
            <input type='button' value='Edit' class='btn btn-success short' onclick=''>
        </div>
        ";

    echo "
    </div>
    ";
}

?>

<div class='album'>
    <div class='container'>
        <h2 class="alert alert-primary">Card Album</h2>

        <div class='container'>
            
            <h3>Positions:</h3>
            <?php
            foreach($lang['positions'] as $pos)
            {
                DrawPosCard($pos);
            }
            ?>

            <h3>Questions:</h3>
            <?php
            foreach($lang['questions'] as $que)
            {
                DrawQuestionCard($que);
            }
            ?>
        </div>

    </div>
</div>