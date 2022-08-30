<?php
require('category.php');
/*
-   Card
    - text
*/
class Card{
    public $text;

    function __construct($text)
    {
        $this->text = $text;
    }
}

/*
-   Position : Card
    -   bonuses[] : Category
    -   smears[] : Category
*/
class Position extends Card{
    public $bonuses = array();
    public $smears = array();
    
    function __construct($text)
    {
        parent::__construct($text);
    }

    function AddBonus($id, $points){
        $this->bonuses[] = new Category($id, $points);
    }

    function AddSmear($id, $points){
        $this->smears[] = new Category($id, $points);
    }
}

/*
-   Question : Card
    -   Answers[] : Position
*/
class Question extends Card{
    public $answers = array();

    function __construct($text)
    {
        parent::__construct($text);
    }

    //Pass in a Position object.
    function AddPosition($position){
        $this->answers[] = $position;
    }

}

?>