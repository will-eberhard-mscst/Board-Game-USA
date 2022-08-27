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

?>

<body>
    <div>
        <h2 class="alert alert-success">Create Cards</h2>

        <form method='post'>

            <p>Select the card type:</p>
            <div>
                <input type="radio" name="card_type" id="position" value="Position" checked>
                <label for="position">Position</label>
                <input type="radio" name="card_type" id="question" value="Question">
                <label for="question">Question</label>
            </div>

            <div><label for="text">Text</label></div>
            <div><input type='text' name='text'></div>
            
            <div><label for="bonuses">Bonuses</label></div>

            <div class='container'>
                <div><label for="bonuses[0][id]">Category</label></div>
                <div>
                    <select name='bonuses[0][id]'>
                        <?=$cat_options?>
                    </select>
                </div>

                <div><label for="bonuses[0][points]">Points</label></div>
                <div><input type='number' name='bonuses[0][points]' value='0'></div>

                <div><input type='button' value='Add More'></div>
            </div>


            <div><label for="smears">Smears</label></div>

            <div class='container'>
                <div><label for="smears[0][id]">Category</label></div>
                <div>
                    <select name='smears[0][id]'>
                        <?=$cat_options?>
                    </select>
                </div>

                <div><label for="smears[0][points]">Points</label></div>
                <div><input type='number' name='smears[0][points]' value='0'></div>

                <div><input type='button' value='Add More'></div>
            </div>


            <div><input type="submit" value="Submit"></div>

        </form>
    </div>
</body>