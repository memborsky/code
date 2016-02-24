<?php

namespace ClockSolitare;

include "class.decks.php";


// Our suit colors
$suit_colors = array
(
    "C" => "black",
    "D" => "red",
    "H" => "red",
    "S" => "black"
);


$deck = null;
$piles = null;


/**
 * Setup a new game for us to play.
 */
function create_new_game()
{
    global $deck, $piles;

    $deck = new Decks();
    $piles = array();

    /**
     * Create the 13 piles of cards
    **/
    for ($index = 0; $index < 13; $index++)
    {
        $piles[CARD_FACES[$index]] = new Piles();
    }

    $piles["W"] = new Piles();
}


/**
 * Destroy our current game so we don't leave objects around hogging memory.
 */
function destroy_game()
{
    unset($deck);
    unset($piles);

    // Force garbage collection
    gc_collect_cycles();
}


/**
 * Go through the process of putting the given card onto the right pile.
 */
function play_card($card)
{
    global $piles, $suit_colors;

    $card_face = $card->GetFace();
    $card_suit = $card->GetSuit();

    /**
     * Discard to wasted pile if both are true:
     * 1.) the pile we are playing onto is not empty
     * 2.) the pile's top card suit matches ours in color
     */
    if (
            !$piles[$card_face]->isEmpty()
            && $suit_colors[$piles[$card_face]->top()->GetSuit()] == $suit_colors[$card->GetSuit()]
        )
    {
        $piles["W"]->push($card);
        return;
    }

    $piles[$card_face]->push($card);
}


// Continue playing the game until something besides y is input.
while (strtolower(readline("Would you like to play a new game? (y/n) ")) == "y")
{
    // Create a new game and set the current hand to the deck.
    create_new_game();
    $current_hand = $deck;

    // Play the game.
    for ($pass = 0; $pass < 3; $pass++)
    {
        for ($current_hand->rewind(); $current_hand->valid(); $current_hand->next())
        {
            play_card($current_hand->current());
        }

        $current_hand = $piles["W"];
        if ($pass < 2)
        {
            unset($piles["W"]);
            $piles["W"] = new Piles();
        }
    }


    $output = "";

    // Output the results of the game.
    foreach ($piles as $face => $pile)
    {
        $output .= str_pad($face, 4, " ", STR_PAD_LEFT) . " : ";

        for ($pile->rewind(); $pile->valid(); $pile->next())
        {
            $current_card = $pile->current();

            $output .= str_pad(
                        $current_card->GetFace() . $current_card->GetSuit(),
                        4,
                        " ",
                        STR_PAD_RIGHT
                    );
        }

        $output .= "\n";
    }

    print $output . "\n";

    // Destroy our game before we attempt to start up a new one.
    destroy_game();
}


?>
