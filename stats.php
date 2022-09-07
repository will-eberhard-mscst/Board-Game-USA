<?php
//Read the JSON file
$json_filepath = "data/cards.json";
$json = file_get_contents($json_filepath);

// Decode the JSON file
$json_data = json_decode($json, true);
$lang = $json_data['eng'];


$header_row = "
<tr>
    <th>Category</th>
    <th>Num of Cards (+)</th>
    <th>Num of Cards (-)</th>
    <th>Sum of Points (+)</th>
    <th>Sum of Points (-)</th>
</tr>
";

/*
Hard Coded values for number of occurences per state.
*/
$occurances = array(
    "jus" => 13,
    "env" => 10,
    "tra" => 25,
    "job" => 14,
    "eco" => 21,
    "glo" => 10,
    "wel" => 23,
    "nat" => 14,
    "tax" => 17,
    "sec" => 15,
    "pat" => 0
);


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
$cat_totals = new CategoryTotals();//array();
$question_totals = new CategoryTotals();//array();
foreach($lang['categories'] as $cat){
    //If not empty, add to the array.
    $id = $cat['id'];
    if(!empty(trim($id))){
        $cat_totals->Totals[$id] = new CategoryTotal($id);
        $question_totals->Totals[$id] = new CategoryTotal($id);
    }
}



/*
Count the number of Plus and Minus points for each Position Category.
*/
foreach($lang['positions'] as $card)
{

    if(isset($card['bonuses']) && count($card['bonuses']) > 0){
        //Count the Number of Bonus Cards.
        $cat_totals->CountBonusCard();
    }

    if(isset($card['smears']) && count($card['smears']) > 0){
        //Count the Number of Smear Cards.
        $cat_totals->CountSmearCard();
    }

    foreach($cat_totals->Totals as $key => $cat){

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

/*
Count all the totals for Question Cards now.
*/
foreach($lang['questions'] as $question){
    foreach($question['answers'] as $card)
    {
        //Make this a Function that returns an Array OR part of the CategoryTotals class
        if(isset($card['bonuses']) && count($card['bonuses']) > 0){
            //Count the Number of Bonus Cards.
            $question_totals->CountBonusCard();
        }
    
        if(isset($card['smears']) && count($card['smears']) > 0){
            //Count the Number of Smear Cards.
            $question_totals->CountSmearCard();
        }

        
        foreach($question_totals->Totals as $key => $cat){

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
}
?>


<div class='album'>
    <div class='container'>
        <h2 class="alert alert-primary">Card Stats</h2>

        <div class='container'>

            <h3>Categories</h3>
            <div class='container totals'>
                <table class='stats bonus'>
                    <caption>Categories Per State:</caption>
                    <thead>                        
                        <tr>
                            <th>Category</th>
                            <th>Occurences</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($occurances as $cat => $occurance){
                        ?>
                            <tr>
                                <td><?=GetCategoryName($cat)?></td>
                                <td><?=$occurance?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <h3>Positions:</h3>
            <div class='container totals'>
                <div><strong>Total Position Cards:</strong> <?=count($lang['positions'])?></div>
                <div><strong>Total Bonus Cards:</strong> <?=$cat_totals->num_bonus_cards?></div>
                <div><strong>Total Smear Cards:</strong> <?=$cat_totals->num_smear_cards?></div>
                
                <table class='stats bonus'>
                    <caption>Bonuses per Category:</caption>
                    <thead>                        
                        <?=$header_row?>
                    </thead>
                    <tbody>
                        <?php
                        foreach($cat_totals->Totals as $cat => $totals){
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
                    <tfoot>
                        <tr>
                            <td><strong>Totals:</strong></td>
                            <td><?=$cat_totals->GetNumBonusesPlus()?></td>
                            <td><?=$cat_totals->GetNumBonusesMinus()?></td>
                            <td><?=$cat_totals->GetSumBonusesPlus()?></td>
                            <td><?=$cat_totals->GetSumBonusesMinus()?></td>
                        </tr>
                    </tfoot>
                </table>

                <table class='stats smear'>
                    <caption>Smears per Category:</caption>
                    <thead>
                        <?=$header_row?>
                    </thead>
                    <tbody>
                        <?php
                        foreach($cat_totals->Totals as $cat => $totals){
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
                    <tfoot>
                        <tr>
                            <td><strong>Totals:</strong></td>
                            <td><?=$cat_totals->GetNumSmearsPlus()?></td>
                            <td><?=$cat_totals->GetNumSmearsMinus()?></td>
                            <td><?=$cat_totals->GetSumSmearsPlus()?></td>
                            <td><?=$cat_totals->GetSumSmearsMinus()?></td>
                        </tr>
                    </tfoot>
                </table>
                
            </div>

            <!---------------------------QUESTIONS:----------------------------------------------->

            <h3>Questions:</h3>
            <div class='container totals'>
                <div><strong>Total Question Cards:</strong>  <?=count($lang['questions'])?></div>
                <div><strong>Total Answers:</strong>  <?=count($lang['questions']) * 3?></div>
                <div><strong>Total Bonus Answers:</strong> <?=$question_totals->num_bonus_cards?></div>
                <div><strong>Total Smear Answer:</strong> <?=$question_totals->num_smear_cards?></div>

                <table class='stats bonus'>
                    <caption>Bonuses per Category:</caption>
                    <thead>                        
                        <?=$header_row?>
                    </thead>
                    <tbody>
                        <?php
                        foreach($question_totals->Totals as $cat => $totals){
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
                    <tfoot>
                        <tr>
                            <td><strong>Totals:</strong></td>
                            <td><?=$question_totals->GetNumBonusesPlus()?></td>
                            <td><?=$question_totals->GetNumBonusesMinus()?></td>
                            <td><?=$question_totals->GetSumBonusesPlus()?></td>
                            <td><?=$question_totals->GetSumBonusesMinus()?></td>
                        </tr>
                    </tfoot>
                </table>

                <table class='stats smear'>
                    <caption>Smears per Category:</caption>
                    <thead>
                        <?=$header_row?>
                    </thead>
                    <tbody>
                        <?php
                        foreach($question_totals->Totals as $cat => $totals){
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
                    <tfoot>
                        <tr>
                            <td><strong>Totals:</strong></td>
                            <td><?=$question_totals->GetNumSmearsPlus()?></td>
                            <td><?=$question_totals->GetNumSmearsMinus()?></td>
                            <td><?=$question_totals->GetSumSmearsPlus()?></td>
                            <td><?=$question_totals->GetSumSmearsMinus()?></td>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>

    </div>
</div>