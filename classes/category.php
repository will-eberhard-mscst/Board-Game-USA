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

?>