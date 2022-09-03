<?php
//Read the JSON file
$json_filepath = "data/cards.json";
$json = file_get_contents($json_filepath);

// Decode the JSON file
$json_data = json_decode($json, true);
$lang = $json_data['eng'];


/*
Returns the Name of the Category given the ID.
*/
function GetCategoryName($id){
    global $lang;
    $categories = $categories = $lang['categories'];

    foreach($categories as $cat){
        if($cat['id'] == $id){
            return $cat['name'];
        }
    }

    return $id;
}


//Create an Associative Array of all the categories
$cat_totals = array();
foreach($lang['categories'] as $cat){
    //If not empty, add to the array.
    $id = $cat['id'];
    if(!empty(trim($id))){
        $cat_totals[$id] = new CategoryTotal($id);
    }
}

/*
Count the number of Plus and Minus points for each Category.
*/
foreach($lang['positions'] as $card)
{
    foreach($cat_totals as $key => $cat){
    
        foreach($card['bonuses'] as $obj){
            if($obj['id'] == $key){

                $points = $obj['points'];

                //Record any Pluses or Minuses for each Category:
                if($points > 0){
                    $cat->BonusTotalPlus->Count();
                    $cat->BonusTotalPlus->AddToSum($points);
                }
                else{
                    $cat->BonusTotalMinus->Count();
                    $cat->BonusTotalMinus->AddToSum($points);
                }

            }
        }

        if(isset($card['smears'])){
            foreach($card['smears'] as $obj){
                if($obj['id'] == $key){

                    //Record any Pluses or Minuses for each Category:
                    $points = $obj['points'];

                    //Record any Pluses or Minuses for each Category:
                    if($points > 0){
                        $cat->SmearTotalPlus->Count();
                        $cat->SmearTotalPlus->AddToSum($points);
                    }
                    else{
                        $cat->SmearTotalMinus->Count();
                        $cat->SmearTotalMinus->AddToSum($points);
                    }

                }
            }
        }

    }
}
?>


<div class='album'>
    <div class='container'>
        <h2 class="alert alert-primary">Card Stats</h2>

        <div class='container'>
            
            <h3>Positions:</h3>
            <div class='container totals'>
                <div><strong>Total Position Cards:</strong> <?=count($lang['positions'])?></div>

                
                <table class='stats'>
                    <caption>Bonuses per Category:</caption>
                    <thead>                        
                        <tr>
                            <th>Category</th>
                            <th>Num of Cards (+)</th>
                            <th>Num of Cards (-)</th>
                            <th>Sum of Points (+)</th>
                            <th>Sum of Points (-)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($cat_totals as $cat => $totals){
                        ?>
                            <tr>
                                <td><?=GetCategoryName($cat)?></td>
                                <td><?=$totals->BonusTotalPlus->count?></td>
                                <td><?=$totals->BonusTotalMinus->count?></td>
                                <td><?=$totals->BonusTotalPlus->sum?></td>
                                <td><?=$totals->BonusTotalMinus->sum?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>

                <table class='stats'>
                    <caption>Smears per Category:</caption>
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Num of Cards (+)</th>
                            <th>Num of Cards (-)</th>
                            <th>Sum of Points (+)</th>
                            <th>Sum of Points (-)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($cat_totals as $cat => $totals){
                        ?>
                            <tr>
                                <td><?=GetCategoryName($cat)?></td>
                                <td><?=$totals->SmearTotalPlus->count?></td>
                                <td><?=$totals->SmearTotalMinus->count?></td>
                                <td><?=$totals->SmearTotalPlus->sum?></td>
                                <td><?=$totals->SmearTotalMinus->sum?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                
            </div>

            <h3>Questions:</h3>
            <div>Total count: <?=count($lang['questions'])?></div>
            
        </div>

    </div>
</div>