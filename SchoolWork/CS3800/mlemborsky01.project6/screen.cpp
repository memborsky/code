#include <iostream>
#include <unistd.h>

#include "screen.h"

using namespace std;

screen::screen()
{
  NULL;
}

void screen::clearScreen()
{
  cout << "\033[2J";
}

void screen::moveCursor(int nX, int nY)
{
  cout << "\033[" << nY << ";" << nX << "H";
}

void screen::printString(string strInput, int nStartX, int nStartY, int nSleep = 25000, bool bReverse)
{
  int nIndex = 0;
  int nX = nStartX;
  int nY = nStartY;

  if (bReverse)
  {

    for (nIndex = strInput.length(); nIndex >= 0; nIndex--)
    {
      moveCursor(nX--, nY);
      cout << strInput[nIndex];
      cout.flush();
      usleep(nSleep);
    }

  }
  else
  {

    for (nIndex = 0; nIndex <= strInput.length(); nIndex++)
    {
      moveCursor(nX++, nY);
      cout << strInput[nIndex];
      cout.flush();
      usleep(nSleep);
    }

  }
}

void screen::printChar(char strInput, int nX, int nY, int nFGColor, int nBGColor, int nAttribute, int nSleep)
{
  moveCursor(nX, nY);
  cout << "\033[" << nAttribute << ";" << nBGColor << ";" << nFGColor << "m" << strInput << "\033[0m";
  cout.flush();
  usleep(nSleep);
}
