<?php
require('classes/card.php');
/*
Date: 8/27/2022

http://localhost/cardmaker/layout.php?page=add

Classes:
-   category:
    -   id
    -   points
-   Card
    - text
-   Position : Card
    -   bonuses[] : Category
    -   smears[] : Category
-   Question : Card
    -   Answers[] : Position

Features:
-   Switch between creating Position and Question cards.
-   Add Position cards with:
    -   text
    -   bonuses (categories)
        -   id
        -   points
    -   Button to add more bonuses
    -   smears (categories)
        -   id
        -   points
    -   Button to add more smears
-   Add Question cards with
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

-   Use this same page to Edit existing Cards.
*/

//Read the JSON file
$json_filepath = "data/cards.json";
$json = file_get_contents($json_filepath);

// Decode the JSON file
$json_data = json_decode($json);

// Display data
//echo "<pre><code>" . print_r($json_data) . "</code></pre>";

$lang = $json_data->eng;
$categories = $lang->categories;

$cat_options = "";

foreach($categories as $cat){
    $id = $cat->id;
    $name = $cat->name;
    $desc = $cat->desc;

    $cat_options .= "<option value='$id'><strong>$name</strong> - $desc</option>";
}


/*
Add a Bonus tag:
*/
function AddBonus($position_no, $bonus_no){
    global $cat_options;

    return "
    <div class='bonus'>
        <div><label>Bonus #$bonus_no</label></div>
        <div><label for='bonuses[$position_no][$bonus_no][id]'>Category</label></div>
        <div>
            <select class='chosen-select' name='bonuses[$position_no][$bonus_no][id]'>
                $cat_options
            </select>
        </div>

        <div><label for='bonuses[$position_no][$bonus_no][points]'>Points</label></div>
        <div><input type='number' name='bonuses[$position_no][$bonus_no][points]' value='0'></div>
    </div>
    <hr>
    ";
}

/*
Add a Smear tag:
*/
function AddSmear($position_no, $smear_no){
    global $cat_options;

    return "
    <div class='smear'>
        <div><label>Smear #$smear_no</label></div>
        <div><label for='smears[$position_no][$smear_no][id]'>Category</label></div>
        <div>
            <select class='chosen-select' name='smears[$position_no][$smear_no][id]'>
                $cat_options
            </select>
        </div>

        <div><label for='smears[$position_no][$smear_no][points]''>Points</label></div>
        <div><input type='number' name='smears[$position_no][$smear_no][points]' value='0'></div>
    </div>
    <hr>
    ";
}

/*
AJAX:
Get the function name that is trying to be called:
*/
if(isset($_POST['functionname'])){

    $func = $_POST['functionname'];

    switch($func){
        case 'AddBonus':
            echo AddBonus($_POST['data0'], $_POST['data1']);
            break;

        case 'AddSmear':
            echo AddSmear($_POST['data0'], $_POST['data1']);
            break;
    }

    exit;
}

/*
Add to JSON file:
*/
if(isset($_POST["submit"]))
{
    $card_type = $_POST["card_type"];

    //Array that contains the type of card we want to upload:
    $array;

    //Select the card array to get based on the card type.
    switch($card_type){
        case "Position":
            $array = $lang->positions;
            break;
        
        case "Question":
            $array = $lang->questions;
            echo "Questions are not supported yet";
            exit;
            break;
    }

    /*
    If the text is empty OR (there are 0 bonuses and 0 Smears, then prevent the submission)
    */
    if(empty($_POST["text"][0]) || (empty($_POST["bonuses"][0][0]["id"]) && empty($_POST["smears"][0][0]["id"])) ){
        echo "<div class='alert alert-danger'>ERROR: You didn't enter any text, bonuses or Smears.</div>";
        exit;
    }

    //Each text is an indicator of how many Positions exist.
    $i = 0;
    foreach($_POST["text"] as $text){
        $card = new Position($text);


        //Add Each Bonus as long as they are not empty:
        foreach($_POST["bonuses"][$i] as $bonus){
            if(!empty($bonus["id"]) && !empty($bonus["points"])){
                $card->AddBonus($bonus["id"], $bonus["points"]);
                //echo $bonus["id"] . " ". $bonus["points"];
            }            
        }

        //Add Each Smear as long as they are not empty:
        foreach($_POST["smears"][$i] as $smear){
            if(!empty($smear["id"]) && !empty($smear["points"])){
                $card->AddSmear($smear["id"], $smear["points"]);
                //echo $card->smears[$i]->id . " ". $card->smears[$i]->points;
            }            
        }

        //Add the card to the array:
        $array[] = $card;        

        $i++;
    }


    //Update the array in the JSON data:
    switch($card_type){
        case "Position":
            $json_data->eng->positions = $array;
            break;
        
        case "Question":
            echo "Questions are not supported yet";
            exit;
            break;
    }
    
    $new_json = json_encode($json_data);

    //Update the Json file.
    if (file_put_contents($json_filepath, $new_json))
        echo "<div class='alert alert-success'>Card added successfully!</div>";
    else 
        echo "<div class='alert alert-danger'>Uh-Oh! Error when updaing json file!</div>";

    //echo "<pre><code>" . print_r($new_json) . "</code></pre>";
}

?>


<div class='create'>
    <div class='container'>
        <h2 class="alert alert-primary">Create Card</h2>

        <form method='post'>

            <div><label>Select the card type:</label></div>
            <div>
                <input type="radio" name="card_type" id="position" value="Position" checked>
                <label for="position">Position</label>
                <!--
                <input type="radio" name="card_type" id="question" value="Question">
                <label for="question">Question</label>
                -->
            </div>


            <div><h2 class='heading'>Position #0</h2></div>

            <div class='container inner'>
                <div><label for="text[0]">Text</label></div>
                <div><textarea name='text[0]'></textarea></div>

                
                <div><h3 class='heading'>Bonuses</h3></div>

                <div class='container inner'>
                    <div id='bonuses'>
                        <?=AddBonus(0,0)?>
                    </div>
                    <div><input type='button' value='Add More' class='btn btn-primary' onclick='AddBonus()'></div>
                </div>
                

                <div><h3 class='heading'>Smears</h3></div>

                <div class='container inner'>
                    <div id='smears'>
                        <?=AddSmear(0, 0)?>
                    </div>
                    <div><input type='button' value='Add More' class='btn btn-primary' onclick='AddSmear()'></div>
                </div>
            </div>  

            <div><input type="submit" name='submit' value="Submit" class='btn btn-success'></div>

        </form>
    </div>
</div>


