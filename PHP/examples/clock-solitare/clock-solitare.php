<?php

/**
 * Setup:
 * Use one deck of cards. Shuffle it and you are ready to go. Imagine a clock on your game table. All ones will be placed at the 1 o'clock position, all Queens on 12 o'clock, and the kings in the center. This game has no cascades. In the figure the stock and waste are inside the clock. If playing the game with a real deck you would hold the stock in your hand and put the waste at a convenient location outside the clock.
 *
 * Rules:
 * Draw card by card from the stock. If one fits to any of the 13 foundations play it, otherwise put it on the waste. Foundations are built by alternating color and layer wise. This means that you can choose your starting suit, but the second card on the foundation must be opposite color. Layer wise by suit means that you must have the same suit on each layer of the foundations. So on all foundations you must have - for example - Spades, Diamonds, Clubs, Hearts in the same order, though you can choose the order during the first moves of the game. The game allows you to recycle the waste stack exactly two times.
**/

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
$layers = null;

/**
 * Setup a new game for us to play.
 */
function create_new_game()
{
    global $deck, $piles, $layers;

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

    $layers = array();
}


/**
 * Destroy our current game so we don't leave objects around hogging memory.
 */
function destroy_game()
{
    unset($deck);
    unset($piles);
    unset($layers);

    // Force garbage collection
    gc_collect_cycles();
}


/**
 * Go through the process of putting the given card onto the right pile.
 */
function play_card($card)
{
    global $piles, $suit_colors, $layers;

    $card_face = $card->GetFace();
    $card_suit = $card->GetSuit();

    $pile_count = $piles[$card_face]->count();

    if (isset($layers[$pile_count]))
    {
        // The current layer has been set.

        if ($layers[$pile_count] == $card_suit)
        {
            $piles[$card_face]->push($card);
        }
        else
        {
            $piles["W"]->push($card);
        }
    }
    else
    {
        // The card could be the new layer.

        if ($pile_count == 0)
        {
            // This means we have found the first card in the game.

            $layers[$pile_count] = $card_suit;
            $piles[$card_face]->push($card);
        }
        elseif ($suit_colors[$piles[$card_face]->top()->GetSuit()] != $suit_colors[$card_suit])
        {
            $layers[] = $card_suit;
            $piles[$card_face]->push($card);
        }
        else
        {
            $piles["W"]->push($card);
        }
    }

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


    $output = "Layer :  1   2   3   4\n";
    $output .= "   --------------------\n";

    // Output the results of the game.
    foreach ($piles as $face => $pile)
    {
        if ($face == "W")
        {
            $output .= "   --------------------\n";
        }

        $output .= str_pad($face, 5, " ", STR_PAD_LEFT) . " : ";

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
