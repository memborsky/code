#include <iostream>

using namespace std;

#include "card.h"
#include "deck.h"
#include "pile.h"

card objCard;
deck objDeck;
pile objPile;

void printGame ()
{
  int nCard = 0;
  int nPile = 0;

  cout << endl << "1    2    3    4    5    6    7    8    9    10   11   12   13" << endl;

  for (nCard = 1; nCard <= 4; nCard++)
  {
    for (nPile = 1; nPile <= 13; nPile++)
    {
      objCard = objPile.getCard(nPile, nCard);
      cout << objCard.getFace() << objCard.getSuit();

      if (nPile != 13)
      {
        cout << "   ";
      }
    }

    cout << endl;
  }
}

void newGame ()
{
  int nCard = 0;
  int nPile = 0;

  objDeck.shuffle();

  for (nCard = 1; nCard <= 4; nCard++)
  {
    for (nPile = 1; nPile <= 13; nPile++)
    {
      objPile.addCard(objDeck.grab(), nPile, nCard);
    }
  }
}

void playGame (card moveCard, int nOldPile)
{
  char strFace = moveCard.getFace();
  char strSuit = moveCard.getSuit();
  int nNewPile = objPile.getPile(strFace);

  cout << "Moving " << strFace << strSuit << " from pile " << nOldPile << " to " << nNewPile << endl;

  objPile.addCard(moveCard, nNewPile, 0);

  // if (!(strFace == objPile.getCard(nNewPile, 5).getFace() && strSuit == objPile.getCard(nNewPile, 5).getSuit()))
  if (!(objPile.isFilled(nNewPile)))
  {
    if (nNewPile == 13)
    {
      objCard = objPile.getCard(nNewPile, 4);
    }
    else
    {
      objCard = objPile.getCard(nNewPile, 5);
    }
    playGame(objCard, nNewPile);
  }
/*
  else
  {
    cout << "Last card on pile is " << objPile.getCard(nNewPile, 5).getFace()
         << objPile.getCard(nNewPile, 5).getSuit() << endl;
  }
*/
}

int main ()
{
  char strTemp;

  cout << "Would you like to play a new game (y/Y)? ";
  cin >> strTemp;

  while (strTemp == 'y' || strTemp == 'Y')
  {
    objPile.newPile();

    newGame();
    printGame();
    playGame(objPile.getCard(13, 4), 13);
    printGame();

    cout << endl << "Would you like to play a new game (y/Y)? ";
    cin >> strTemp;
  }

  return 0;
}
