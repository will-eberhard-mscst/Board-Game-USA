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



$lang = $json_data['eng'];
$categories = $lang['categories'];

//echo "<pre><code>" . print_r($categories) . "</code></pre>";

// Display data
//echo "<pre><code>" . print_r($json_data) . "</code></pre>";

/*
AJAX:
Get the function name that is trying to be called:
*/
if(isset($_POST['functionname'])){

    $func = $_POST['functionname'];

    switch($func){
        case 'DeleteCard':
            echo DeleteCard($_POST['data0'], $_POST['data1']);
            break;
    }

    exit;
}

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
Delete a Card given the uid.
Returns true if deleted.

Card Type:
0 = position
1 = question
*/
function DeleteCard($uid, $card_type){
    global $json_data;
    $lang = $json_data['eng'];

    $type = '';

    switch($card_type){
        case 0: $type = 'positions'; break;
        case 1: $type = 'questions'; break;
    }

    $i = 0;
    $array = $lang[$type];
    foreach($array as $obj)
    {
        if($obj['uid'] == $uid){
            //remove from the array.
            array_splice($array, $i, 1);
            //update the JSON file.
            $json_data['eng'][$type] = $array;
            return SaveFile($json_data);
        }
        $i++;
    }
    
    return false;
}

/*
Save the JSON file.
Returns true on success, false on fail.
*/
function SaveFile($json_data){
    global $json_filepath;

    //Encode the JSON:
    $new_json = json_encode($json_data);

    //Update the Json file.
    return file_put_contents($json_filepath, $new_json);
}

/*
Draws a Position Card.
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

/*
Draw a Question card.
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