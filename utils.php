<?php
/*
This file will be required by add.php and edit.php
*/

//Read the JSON file
$json_filepath = "data/cards.json";
$json = file_get_contents($json_filepath);

// Decode the JSON file
$json_data = json_decode($json);

// Display data
//echo "<pre><code>" . print_r($json_data) . "</code></pre>";



$lang = $json_data->eng;



/*
Returns the Categories and sets one as the Selected option.
If no cat_id is entered, none will be selected.
*/
function GetCategories($cat_id = ""){
    global $lang;

    $categories = $lang->categories;

    $cat_options = "";

    foreach($categories as $cat){
        $id = $cat->id;
        $name = $cat->name;
        $desc = $cat->desc;

        //select the cat_id
        $selected = "";
        if($cat_id == $id){
            $selected = "selected";
        }

        $cat_options .= "<option value='$id' $selected><strong>$name</strong> - $desc</option>";
    }

    return $cat_options;
}


/*
Add a Bonus tag:
*/
function AddBonus($position_no, $bonus_no, stdclass $bonus = null){
    
    $cat_id = "";
    $points = 0;

    if($bonus){
        $cat_id = $bonus->id;
        $points = $bonus->points;
    }

    $cat_options = GetCategories($cat_id);

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
        <div><input type='number' name='bonuses[$position_no][$bonus_no][points]' value='$points'></div>
    </div>
    <hr>
    ";
}

/*
Add a Smear tag:
*/
function AddSmear($position_no, $smear_no, stdclass $smear = null){

    $cat_id = "";
    $points = 0;

    if($smear){
        $cat_id = $smear->id;
        $points = $smear->points;
    }

    $cat_options = GetCategories($cat_id);

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
        <div><input type='number' name='smears[$position_no][$smear_no][points]' value='$points'></div>
    </div>
    <hr>
    ";
}

/*
Add a Position tag:
*/
function AddPosition(int $position_no, stdclass $position_card = null){

    $text = "";
    $bonus_tags = "";
    $smear_tags = "";

    /*
    If we have a valid Position card, set all the default field values.
    */
    if($position_card){
        $text = $position_card->text;
        
        $i = 0;
        foreach($position_card->bonuses as $bonus){
            $bonus_tags .= AddBonus($position_no, $i++, $bonus);
        }

        $i = 0;
        foreach($position_card->smears as $smear){
            $smear_tags .= AddSmear($position_no, $i++, $smear);
        }
    }
    else{
        $bonus_tags = AddBonus($position_no, 0);
        $smear_tags = AddSmear($position_no, 0);
    }

    $tag ="
    <div class='position'>
        <div><h2 class='heading'>Position #$position_no</h2></div>

        <div class='container outer'>
            <div><label for='text[$position_no]'>Text</label></div>
            <div><textarea name='text[$position_no]'>$text</textarea></div>
            
            <div><h3 class='heading'>Bonuses</h3></div>

            <div class='container inner'>
                <div id='bonuses$position_no'>
                    " . $bonus_tags ."
                </div>
                <div><input type='button' value='Add More' class='btn btn-primary long' onclick='AddBonus($position_no)'></div>
            </div>
            

            <div><h3 class='heading'>Smears</h3></div>

            <div class='container inner'>
                <div id='smears$position_no'>
                    ". $smear_tags ."
                </div>
                <div><input type='button' value='Add More' class='btn btn-primary long' onclick='AddSmear($position_no)'></div>
            </div>
        </div>  
    </div>
    ";
    
    return $tag;
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

        case 'AddPosition':
            echo AddPosition($_POST['data0']);
            break;
    }

    exit;
}

?>