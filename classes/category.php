<?php

/*
-   category:
    -   id
    -   points
*/
class Category{

    //Properties
    public $id;
    public $points;

    function __construct($id, $points)
    {
        $this->id = $id;
        $this->points = $points;
    }

}

/*
Stores the id, number of occurances, and the sum of all the points.
*/
class Total{
    public $id;
    public $count = 0;
    public $sum = 0;

    function __construct($id)
    {
        $this->id = $id;
    }

    //Increase the Count by 1
    function Count(){
        $this->count++;
    }

    //Add this value to the sum.
    function AddToSum($value){
        $this->sum += $value;
    }
}

/*
Stores the totals of each Category.
That includes the Total count of Plus and Minus cards for this category for both Bonuses and Smears.
*/
class CategoryTotal{
    public $BonusTotalPlus;
    public $BonusTotalMinus;
    public $SmearTotalPlus;
    public $SmearTotalMinus;

    function __construct($id)
    {
        $this->BonusTotalPlus = new Total($id);
        $this->BonusTotalMinus = new Total($id);
        $this->SmearTotalPlus = new Total($id);
        $this->SmearTotalMinus = new Total($id);
    }
}

/*
Calculate the Footer Totals for the cat_totals
*/
class CategoryTotals{
    public $Totals = array();

    //Number of cards with bonuses
    public $num_bonus_cards = 0;

    //Number of cards with smears
    public $num_smear_cards = 0;

    function __construct()
    {
        
    }

    function CountBonusCard(){
        $this->num_bonus_cards++;
    }

    function CountSmearCard(){
        $this->num_smear_cards++;
    }

    /*
    Calculate the number of total bonuses plus
    */
    function GetNumBonusesPlus(){
        $num = 0;
        foreach($this->Totals as $totals){
            $num += $totals->BonusTotalPlus->count;
        }
        return $num;
    }

}

?>