<?php
require('category.php');
/*
-   Card
    - text
*/
class Card{
    public $uid;
    public $text;

    function __construct($uid, $text)
    {
        $this->uid = $uid;
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
    
    function __construct($uid, $text)
    {
        parent::__construct($uid, $text);
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

    function __construct($uid, $text)
    {
        parent::__construct($uid, $text);
    }

    //Pass in a Position object.
    function AddPosition($position){
        $this->answers[] = $position;
    }

}

?>