#include "card.h"

const char card::ma_cardFaces[] = {'2', '3', '4', '5', '6', '7', '8', '9', 'T', 'J', 'Q', 'K', 'A'};
const char card::ma_cardSuits[] = {'C', 'D', 'H', 'S'};

// Initialize
card::card ()
{
  m_card = 0;
}

// Initialize to non-standard card.
card::card (int card)
{
  m_card = card;
}

// Return card face value.
char card::getFace () const
{
  return ma_cardFaces[m_card % 13];
}

// Return card suit.
char card::getSuit () const
{
  return ma_cardSuits[m_card / 13];
}
