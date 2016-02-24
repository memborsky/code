<?php

namespace ClockSolitare;

const CARD_FACES = array('A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K');
const CARD_SUITS = array('C', 'D', 'H', 'S');

/**
 *
**/
class Cards
{
    // The card's suite and face.
    protected $card_face;
    protected $card_suit;


    public function __construct ($card)
    {
        $this->card_face = CARD_FACES[$card % 13];
        $this->card_suit = CARD_SUITS[$card / 13];
    }


    public function GetFace ()
    {
        return $this->card_face;
    }


    public function GetSuit ()
    {
        return $this->card_suit;
    }
}

?>
