<?php
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
    -   bonuses (categories)
        -   id
        -   points
    -   Button to add more bonuses
    -   smears (categories)
        -   id
        -   points
    -   Button to add more smears
*/

//Read the JSON file
$json = file_get_contents("data/cards.json");

// Decode the JSON file
$json_data = json_decode($json);

// Display data
//echo "<pre><code>" . print_r($json_data) . "</code></pre>";

$lang = "eng";
$categories = $json_data->eng->categories;

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
function AddBonus($bonus_no){
    global $cat_options;

    return "
    <div class='bonus'>
        <div><label>Bonus #$bonus_no</label></div>
        <div><label for='bonuses[$bonus_no][id]'>Category</label></div>
        <div>
            <select class='chosen-select' name='bonuses[0][id]'>
                $cat_options
            </select>
        </div>

        <div><label for='bonuses[$bonus_no][points]'>Points</label></div>
        <div><input type='number' name='bonuses[0][points]' value='0'></div>
    </div>
    <hr>
    ";
}

/*
Add a Smear tag:
*/
function AddSmear($no){
    global $cat_options;

    return "
    <div class='smear'>
        <div><label>Smear #$no</label></div>
        <div><label for='smears[$no][id]'>Category</label></div>
        <div>
            <select class='chosen-select' name='smears[$no][id]'>
                $cat_options
            </select>
        </div>

        <div><label for='smears[$no][points]''>Points</label></div>
        <div><input type='number' name='smears[$no][points]' value='0'></div>
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
            echo AddBonus($_POST['data']);
            break;

        case 'AddSmear':
            echo AddSmear($_POST['data']);
            break;
    }

    exit;
}

?>


<div class='create'>
    <div class='container'>
        <h2 class="alert alert-primary">Create Cards</h2>

        <form method='post'>

            <div><label>Select the card type:</label></div>
            <div>
                <input type="radio" name="card_type" id="position" value="Position" checked>
                <label for="position">Position</label>
                <input type="radio" name="card_type" id="question" value="Question">
                <label for="question">Question</label>
            </div>

            <div><label for="text">Text</label></div>
            <div><textarea name='text'></textarea></div>

            
            <div><h3 class='heading'>Bonuses</h3></div>

            <div class='container inner'>
                <div id='bonuses'>
                    <?=AddBonus(0)?>
                </div>
                <div><input type='button' value='Add More' class='btn btn-primary' onclick='AddBonus()'></div>
            </div>
            

            <div><h3 class='heading'>Smears</h3></div>

            <div class='container inner'>
                <div id='smears'>
                    <?=AddSmear(0)?>
                </div>
                <div><input type='button' value='Add More' class='btn btn-primary' onclick='AddSmear()'></div>
            </div>


            <div><input type="submit" value="Submit" class='btn btn-success'></div>

        </form>
    </div>
</div>


