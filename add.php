<?php
require('utils.php');
/*
Date: 8/27/2022

http://localhost/cardmaker/layout.php?page=add

Classes:
-   category:
    -   id
    -   points
-   Card
    -   uid
    -   text
-   Position : Card
    -   bonuses[] : Category
    -   smears[] : Category
-   Question : Card
    -   Answers[] : Position

Features:
DONE -   Switch between creating Position and Question cards.
DONE -   Add Position cards with:
    -   text
    -   bonuses (categories)
        -   id
        -   points
    -   Button to add more bonuses
    -   smears (categories)
        -   id
        -   points
    -   Button to add more smears
DONE -   Add Question cards with
    -   text
        -   Postion:
            -   text
            -   bonuses (categories)
                -   id
                -   points
            -   Button to add more bonuses
            -   smears (categories)
                -   id
                -   points
            -   Button to add more smears
        -   Button to Add more Positions


DATE: 9/2/2022
-   Use this same page to Edit existing Cards.

DONE -   uid will be a parameter. Without a UID, this page is useless.
DONE -   All the current values will be set as the default values.
-   On submit, delete all current positions, smears, and bonuses and submit the new card as is.
-   There is a bug when adding new Bonuses or Smears where the number starts at 0 even if there are defaulted bonuses or smears.
*/

/*
If this is an Edit, get all the Data for the entry we need to edit.
*/
$isEdit = isset($_GET['uid']);

$heading = "Create Card";

if($isEdit){

    $heading = "Edit Card";

    $card = GetCard($_GET['uid']);

    $card_type = GetCardType($card);
}

/*
Returns a card given its UID
*/
function GetCard($uid){
    global $json_data;
    $lang = $json_data->eng;

    //Check the position cards
    $i = 0;
    $array = $lang->positions;
    foreach($array as $obj)
    {
        if($obj->uid == $uid){
            return $obj;
        }
        $i++;
    }

    //Check the Question cards
    $array = $lang->questions;
    foreach($array as $obj)
    {
        if($obj->uid == $uid){
            return $obj;
        }
        $i++;
    }

    return false;
}

/*
Returns a string with the card type.
*/
function GetCardType($card){
    //returns 0 for position, 1 for Question.
    $card_type = isset($card->answers);

    $type = '';

    switch($card_type){
        case 0: $type = 'Position'; break;
        case 1: $type = 'Question'; break;
    }

    return $type;
}


/*
Add to JSON file:
*/
if(isset($_POST["submit"]))
{
    $card_type = $_POST["card_type"];
    
    /*
    Get the last Card uid
    The uid is unique to every card and position.
    */
    $uid = $json_data->card_uid_counter;

    //Whatever the uid is now is our card's uid.
    $card_uid = $uid;    
    

    //Array that contains the type of card we want to upload:
    $array;
    $delete_array;

    //Select the card array to get based on the card type.
    switch($card_type){
        case "Position":
            $array = $lang->positions;
            $delete_array = $array;
            break;
        
        case "Question":
            //Show an error if the Question text is empty:
            if(empty($_POST['question_text'])){
                echo "<div class='alert alert-danger'>ERROR: You didn't enter any Question Text!</div>";
                exit;
            }

            //Create a new question using the Question Text.
            $question = new Question($uid++, $_POST['question_text']);

            //We will use the Answers array to get the positions in this case:
            $array = $question->answers;
            $delete_array = $lang->questions;
            break;
    }
    

    /*
    If the text is empty OR (there are 0 bonuses and 0 Smears, then prevent the submission)
    */
    if(empty($_POST["text"][0]) || (empty($_POST["bonuses"][0][0]["id"]) && empty($_POST["smears"][0][0]["id"])) ){
        echo "<div class='alert alert-danger'>ERROR: You didn't enter any text, bonuses or Smears.</div>";
        exit;
    }

    /*
    If this is an edit, find the old card UID, then remove it from the file.
    */
    if($isEdit){

        $i = 0;
        foreach($delete_array as $obj)
        {
            if($obj->uid == $_GET['uid']){
                //remove from the array.
                array_splice($delete_array, $i, 1);

                //update the JSON file.
                if($card_type == "Position"){
                    $json_data->eng->positions = $delete_array;
                    //Update the array for adding new positions.
                    $array = $delete_array;
                    break;
                }
                else if($card_type == "Question"){
                    $json_data->eng->questions = $delete_array;
                    break;
                }
            }
            $i++;
        }
    }
   

    //Each text is an indicator of how many Positions exist.
    $i = 0;
    foreach($_POST["text"] as $text){
        $position = new Position($uid++, $text);

        //Add Each Bonus as long as they are not empty:
        foreach($_POST["bonuses"][$i] as $bonus){
            if(!empty($bonus["id"]) && !empty($bonus["points"])){
                $position->AddBonus($bonus["id"], $bonus["points"]);
            }            
        }

        //Add Each Smear as long as they are not empty:
        foreach($_POST["smears"][$i] as $smear){
            if(!empty($smear["id"]) && !empty($smear["points"])){
                $position->AddSmear($smear["id"], $smear["points"]);
            }            
        }

        //Add the position card to the array:
        $array[] = $position;        

        $i++;
    }

    

    //Update the uid counter:
    $json_data->card_uid_counter = $uid;


    //Update the array in the JSON data:
    switch($card_type){
        case "Position":
            $json_data->eng->positions = $array;
            break;
        
        case "Question":
            //Update the question's answers and then add the question to the questions array.
            $question->answers = $array;
            $json_data->eng->questions[] = $question;
            break;
    }
    
    //Encode the JSON:
    $new_json = json_encode($json_data);

    //Update the Json file.
    if (file_put_contents($json_filepath, $new_json)){
        echo "<div class='alert alert-success'>Card added successfully!</div>";

        //If this was an edit, update the page with the current card values OR redirect to Album.
        if($isEdit){
            //redirect to the album page.
            header("Location: ?page=album");
            //Edits are given a New UID.
            //header("Location: ?page=add&uid=$card_uid");
        }
    }
    else {
        echo "<div class='alert alert-danger'>Uh-Oh! Error when updating json file!</div>";
    }
}

?>

<!-------------------------------------------------BELOW is the VIEW:--------------------------------------->
<div class='create'>
    <div class='container'>
        <h2 class="alert alert-primary"><?=$heading?></h2>

        <div class='alert alert-secondary'>
            <h5>NOTE:</h5>
            <ul>
                <li>If you want to delete a Bonus or a Smear, set the Points to 0.</li>
                <?php
                if(!$isEdit){
                    echo "<li>To Clear your entry click on either radio button.</li>";
                }
                ?>
            </ul>
        </div>

        <form method='post'>

            <?php
            if(!$isEdit){
            ?>
                <div><label>Select the card type:</label></div>
                <div>
                    <input type="radio" name="card_type" id="position" value="Position" checked>
                    <label for="position">Position</label>
                    <input type="radio" name="card_type" id="question" value="Question">
                    <label for="question">Question</label>                
                </div>
            <?php
            }
            else{
                /*
                Hidden Card Type value. 
                Set the value as either 'Position' or 'Question' based on the current card type.
                */
                
                echo '
                <div>       
                    <input type="text" name="card_type" value="' . $card_type . '" hidden>
                </div>
                ';
            }
            ?>

            <?php
            //On Edit, hidden the Question Text if this is a Position Card.
            $isQuestion = $isEdit && $card_type == "Question";
            $hide = $isQuestion ? '' : 'hidden';

            //Also get the Question text:
            $question_text = $isQuestion ? $card->text : '';

            echo "
            <div id='question_text' $hide>
            ";
            ?>
                <div><label for='question_text'>Question Text</label></div>
                <div><textarea name='question_text'><?=$question_text?></textarea></div>
            </div>

            <div id='positions'>
                <?php
                /*
                Preset the values if this is an Edit.
                */
                if($isEdit){

                    if($card_type == "Question"){
                        $i = 0;
                        foreach($card->answers as $pos){
                            echo AddPosition($i++, $pos);
                        }                        
                    }
                    else if($card_type == "Position"){
                        echo AddPosition(0, $card);
                    }
                }
                else{
                    echo AddPosition(0);
                }
                ?>
            </div>


            <div><input type="submit" name='submit' value="Submit" class='btn btn-success long'></div>

        </form>
    </div>
</div>


