#ifndef CARD_H
#define CARD_H

class card
{
  public:
    // Initialize
    card ();

    // Initialize to non-standard card.
    card (int card);

    // Get the suit of the card.
    char getSuit () const;

    // Get the face value of the card.
    char getFace () const;

  private:
    // Number of cards in the deck.
    int m_card;

    // Lists to hold the face values and suits of the cards.
    static const char ma_cardFaces[];
    static const char ma_cardSuits[];
};

#endif
