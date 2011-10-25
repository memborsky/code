#include <iostream>
#include <cstdlib>

using namespace std;

#include "card.h"
#include "deck.h"

int main ()
{
  int nCards;
  int nIndex;
  char face;
  char suit;
  card obj_card;
  deck obj_deck;

  while (cin >> nCards)
  {
    obj_deck.shuffle();

    for (nIndex = 0; nIndex < nCards; nIndex++)
    {
      obj_card = obj_deck.grab();
      suit = obj_card.getSuit();
      face = obj_card.getFace();
      switch (face)
      {
        case 'T':
          cout << (nIndex + 1) << " = " << face << suit << endl;
          break;
        default:
          break;
      }
    }

    cout << endl;
  }

  return 0;
}
