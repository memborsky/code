#ifndef PILE_H
#define PILE_H

#include "card.h"

class pile
{
  public:
    // Initialize
    pile ();

    // Check if the pile is empty of new cards
    bool isFilled (int nPile);

    // Grab the next card off the top of the pile
    card getCard (int nPile, int nCard);

    // Add a card to the bottom of the pile
    void addCard (card newCard, int nPile, int nCard);

    // Find out which pile to add the cards to
    int getPile (char strFace);

    // Clean up the pile to refresh the game
    void newPile ();

  private:
    struct hPile
    {
      card objCard;
      bool flipped;
    };

    hPile aPile[14][6];
};

#endif