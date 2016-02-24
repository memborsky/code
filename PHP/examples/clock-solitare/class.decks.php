<?php

namespace ClockSolitare;

include "class.cards.php";
include "class.piles.php";

/**
 * This builds our inital deck of cards for the game.
 */
class Decks extends Piles
{
    // Initialize the deck of 52 cards.
    public function __construct()
    {
        // Build the array of cards.
        for ($index = 0; $index < 52; $index++)
        {
            $this->push(new Cards($index));
        }

        // Shuffle the deck.
        $this->Shuffle();
    }


    /**
     * Shuffle the deck.
     */
    public function Shuffle ()
    {
        // Randomly shuffle the deck.
        for ($index = 0; $index < 52; $index++)
        {
            $random = $index + (mt_rand() % (52 - $index));

            if ($index == $random)
            {
                if ($random > 45)
                {
                    $random = $random - ($random % 52);
                }
                elseif ($random < 10)
                {
                    $random = $random + ($random % 52);
                }
            }

            $this->Swap($index, $random);
        }
    }


    /**
     * Swap two cards in the deck.
     *
     * @var $left = card index
     * @var $right = card index
     */
    private function Swap($left, $right)
    {
        // Temp store our right data before we overwrite it.
        $temp = $this->offsetGet($right);

        // Store left data into right index.
        $this->offsetSet($right, $this->offsetGet($left));

        // Store right data into the left index.
        $this->offsetSet($left, $temp);

    }
}


?>
