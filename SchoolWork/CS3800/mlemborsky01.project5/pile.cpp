#include "pile.h"

using namespace std;

// Self-Note: aPile[14][6]

// Initialize
pile::pile()
{
  int nIndex;
  int nJndex;

  for (nIndex = 0; nIndex <= 13; nIndex++)
  {
    for (nJndex = 0; nJndex <= 5; nJndex++)
    {
      aPile[nIndex][nJndex].flipped = false;
    }
  }
}

// Check if the pile is empty of new cards
bool pile::isFilled (int nPile)
{
  bool bResult = false;
  int nIndex;

  if (nPile == 13 && aPile[nPile][4].flipped)
  {
	bResult = true;
  }
  
/*
  for (nIndex = 1; nIndex <= 5; nIndex++)
  {
    if (getPile(aPile[nPile][nIndex].objCard.getFace()) != nPile)
    {
      bResult = false;
    }
  }
*/

  return bResult;
}

// Grab the next card off the top of the pile
card pile::getCard (int nPile, int nCard)
{
  return aPile[nPile][nCard].objCard;
}

// Add a card to the bottom of the pile
void pile::addCard (card newCard, int nPile, int nCard)
{
  int nIndex = 0;

  if (nCard == 0)
  {
    for (nIndex = 5; nIndex >= 1; nIndex--)
    {
      aPile[nPile][nIndex] = aPile[nPile][nIndex - 1];
    }

    nCard++;
    aPile[nPile][nCard].flipped = true;
  }

  aPile[nPile][nCard].objCard = newCard;
}

void pile::newPile ()
{
  int nCard;
  int nPile;

  for (nCard = 1; nCard <= 4; nCard++)
  {
    for (nPile = 1; nPile <= 13; nPile++)
    {
      aPile[nPile][nCard].flipped = false;
    }
  }
}

int pile::getPile (char strFace)
{
  int nResult = 13;

  switch (strFace)
  {
    case 'A':
      nResult = 1;
      break;
    case '2':
      nResult = 2;
      break;
    case '3':
      nResult = 3;
      break;
    case '4':
      nResult = 4;
      break;
    case '5':
      nResult = 5;
      break;
    case '6':
      nResult = 6;
      break;
    case '7':
      nResult = 7;
      break;
    case '8':
      nResult = 8;
      break;
    case '9':
      nResult = 9;
      break;
    case 'T':
      nResult = 10;
      break;
    case 'J':
      nResult = 11;
      break;
    case 'Q':
      nResult = 12;
      break;
    case 'K':
    default:
      nResult = 13;
      break;
  }

  return nResult;
}
