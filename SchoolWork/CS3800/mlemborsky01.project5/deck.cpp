#include <algorithm>
#include <ctime>

using namespace std;

#include "card.h"
#include "deck.h"

deck::deck ()
{
  int nIndex;
  
  for (nIndex = 0; nIndex < 52; nIndex++)
  {
    ma_cards[nIndex] = card(nIndex);
  }

  shuffle();
  mn_nextCard = 0;
}

card deck::grab ()
{
  return ma_cards[mn_nextCard++];
}

void deck::shuffle ()
{
  int nIndex;
  int nRandom;

  srand(time(0));

  for (nIndex = 0; nIndex < 51; nIndex++)
  {
    nRandom = nIndex + (rand() % (52 - nIndex));
    swap(ma_cards[nIndex], ma_cards[nRandom]);
  }

  mn_nextCard = 0;
}
