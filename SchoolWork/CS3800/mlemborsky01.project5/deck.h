#ifndef DECK_H
#define DECK_H

class deck
{
  public:
    // Initialize
    deck ();

    // Grab the top card
    card grab ();

    // Shuffle the deck
    void shuffle ();

  private:
    // Our array of cards;
    card ma_cards[52];

    // Index of next available card.
    int mn_nextCard;
};

#endif