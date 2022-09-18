<?php
/*
This file will be required by add.php and edit.php
*/

//Read the JSON file
$json_filepath = "data/cards.json";
$json = file_get_contents($json_filepath);

// Decode the JSON file
$json_data = json_decode($json, true);

// Display data
//echo "<pre><code>" . print_r($json_data) . "</code></pre>";
$lang = $json_data['eng'];


//Set position and question cards.
$positions = $lang['positions'];
$questions = $lang['questions'];



/*
Returns the Categories and sets one as the Selected option.
If no cat_id is entered, none will be selected.
*/
function GetCategories($cat_id = ""){
    global $lang;

    $categories = $lang['categories'];

    $cat_options = "";

    foreach($categories as $cat){
        $id = $cat['id'];
        $name = $cat['name'];
        $desc = $cat['desc'];

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
function AddBonus($position_no, $bonus_no, $bonus = null){
    
    $cat_id = "";
    $points = 0;

    if($bonus){
        $cat_id = $bonus['id'];
        $points = $bonus['points'];
    }

    $cat_options = GetCategories($cat_id);

    return "
    <div class='bonus bonus$position_no'>
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
function AddSmear($position_no, $smear_no, $smear = null){

    $cat_id = "";
    $points = 0;

    if($smear){
        $cat_id = $smear['id'];
        $points = $smear['points'];
    }

    $cat_options = GetCategories($cat_id);

    return "
    <div class='smear smear$position_no'>
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

/**
 Add a Position tag:
 */
function AddPosition(int $position_no, $position_card = null){

    $text = "";
    $bonus_tags = "";
    $smear_tags = "";

    /*
    If we have a valid Position card, set all the default field values.
    */
    if($position_card){
        $text = $position_card['text'];
        
        $i = 0;
        foreach($position_card['bonuses'] as $bonus){
            $bonus_tags .= AddBonus($position_no, $i++, $bonus);
        }

        //if Smears are not set, create an empty array.
        if(!isset($position_card['smears'])){
            $position_card['smears'] = array();
        }

        $i = 0;
        foreach($position_card['smears'] as $smear){
            $smear_tags .= AddSmear($position_no, $i++, $smear);
        }

        /*
        If either of these have 0 entries, create at least one empty Bonus or Smear.
        */
        if(count($position_card['bonuses']) == 0){
            $bonus_tags = AddBonus($position_no, 0);
        }
        if(count($position_card['smears']) == 0){
            $smear_tags = AddSmear($position_no, 0);
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

/**
Returns the Name of the Category given the ID.
*/
function GetCategoryName($id){
    global $lang;
    $categories = $lang['categories'];

    foreach($categories as $cat){
        if($cat['id'] == $id){
            return $cat['name'];
        }
    }

    return $id;
}

/**
Sorts the Points on a bonus or smear Descending.
 */
function sort_points($a, $b){
    if ($a['points'] == $b['points']) return 0;
    return ($a['points'] > $b['points']) ? -1 : 1;
}


/**
 Returns the HTML for the Categories' images for a Position card's bonuses or smears.
 @param array $category_array Array of Bonuses or Smears
 */
function GetPositionCardImages(Array $category_array){
    $bonus_tag = '';
    $i = 1;

    //Sorts each Bonus or Smear by point value.
    usort($category_array, "sort_points");

    foreach($category_array as $bonus){
        //Place a + sign if the points are positive:
        $positive = $bonus['points'] >= 0;
        $sign = $positive ? '+' : '&minus;';
        //Add the bad class is the points are negative.
        $bad = $positive ? '' : 'bad';

        //Draw a div so each img-block is draw vertically.
        //Start a new Div on each odd number:
        if($i % 2 != 0) $bonus_tag .= '<div class="img-col">';

        $bonus_tag .= '
        <div class="img-block">
            <img class="card-img '.$bad.'" src="/cardmaker/images/sq/cat_' . $bonus['id'] . '_sq.png" alt="Card image cap">
            <span class="points '.$bad.'">'. $sign . abs($bonus['points']) . '</span>
        </div>
        ';

        //close the div on each Even number.
        if($i % 2 == 0) $bonus_tag .= '</div>';

        $i++;
    }
    //If we ended on an even number, ensure we close the div.
    if($i % 2 == 0) $bonus_tag .= '</div>';

    return $bonus_tag;
}

/**
 Returns the HTML for drawing a Position card.
 */
function GetPositionCard($position){

    $bonus_tag = '';

    if(isset($position['bonuses'])){
       $bonus_tag .= GetPositionCardImages($position['bonuses']);
    }

    //check if has smears
    $hasSmears = isset($position['smears']) && count($position['smears']) > 0;
    $smearCard = 'bonus-text';

    if($hasSmears){
        $bonus_tag .= GetPositionCardImages($position['smears']);
        $smearCard = 'smear-text';
    }

    $tag = '
    <div class="card print-card">
        <div class="card-imgs d-flex">
            '. $bonus_tag .'
        </div>
        
        <div class="card-body">
            <p class="card-text '. $smearCard .'"><span>"' .$position['text'] . '"</span></p>
        </div>
    </div>
    ';

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
}



/*
Check for Search queries.
-   Search position text, question text, and answer text. Using the same search field.
-   WHERE the card has a bonus or smear in category X
-   This could potentially be added to the stats page as well.
*/
$category = isset($_GET['category']) ? $_GET['category'] : "";
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : "";
$card_type = isset($_GET['card_type']) ? $_GET['card_type'] : "";

if(isset($_GET["search"]))
{
    
    //If we only want Positions, clear out the Questions
    if($card_type == "Position"){
        $questions = array();
    }
    //If we only want Questions, clear out the positions
    if($card_type == "Question"){
        $positions = array();
    }

    //Search for Bonus or Smear categories
    if(isset($_GET['category']) && !empty($_GET['category'])){

        $filtered_positions = array();
        $filtered_questions = array();

        //Need to check both Bonuses and Smears for the category:
        foreach($positions as $pos){
            foreach($pos['bonuses'] as $obj){
                if($obj['id'] == $category){
                    $filtered_positions[] = $pos;
                    break;
                }
            }            

            if(isset($pos['smears'])){
                foreach($pos['smears'] as $obj){
                    if($obj['id'] == $category){
                        $filtered_positions[] = $pos;
                        break;
                    }
                }   
            }
        }
        

        $positions = $filtered_positions;


        //Now check the question text:        
        //Add the question to the array if it contains the category.
        foreach($questions as $que){

            $que_added = false;

            foreach($que['answers'] as $answer){

                foreach($answer['bonuses'] as $obj){
                    if($obj['id'] == $category){
                        $filtered_questions[] = $que;
                        $que_added = true;
                        break;
                    }
                }

                foreach($answer['smears'] as $obj){
                    if($obj['id'] == $category){
                        $filtered_questions[] = $que;
                        $que_added = true;
                        break;
                    }
                }

                //If the question was added once, break out of the answers loop.
                if($que_added) break;
            }            
        }
        

        $questions = $filtered_questions;
        
    }


    //Search By keyword if a keyword is entered.
    if(isset($_GET['keyword']) && !empty($_GET['keyword'])){

        $filtered_positions = array();
        $filtered_questions = array();
        
        //Add the Position to the array if it contains the word.
        foreach($positions as $pos){
            if(strpos(strtolower($pos['text']), strtolower($keyword)) ){
                $filtered_positions[] = $pos;
            }
        }
        

        $positions = $filtered_positions;


        //Now check the question text:        
        //Add the question to the array if it contains the word.
        foreach($questions as $que){
            if(strpos(strtolower($que['text']), strtolower($keyword)) ){
                $filtered_questions[] = $que;
            }
            /*
            If a question does not contain the word, check if any of its answers do.
            If any of the answers do, add the entire question to the array.
            */
            else{
                foreach($que['answers'] as $answer){
                    if(strpos(strtolower($answer['text']), strtolower($keyword)) ){
                        $filtered_questions[] = $que;
                        break;
                    }
                }
            }
        }
        

        $questions = $filtered_questions;
    }

}

?>