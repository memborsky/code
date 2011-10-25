#include <iostream>
#include <string>
#include <fstream>

#include "screen.h"

using namespace std;

screen objScreen;

struct board
{
  char value;
  int x;
  int y;
};

board aBoard[10][11][5];

struct moves
{
  int row;
  int column;
  int plane;
};

moves aMoves[27];

void buildArray()
{
  ifstream is;
  char file[256];

  objScreen.printString("Please input file name: ", 1, 1, 25000);
  cin.get(file, 256);

  is.open(file);

  int x[5] = {20, 32, 44, 56, 68};
  int y[5] = {5, 6, 7, 8, 9};

  for (int plane = 0; plane <= 4; plane++)
  {
    for (int row = 0; row <= 9; row++)
    {
      for (int column = 0; column <= 10; column++)
      {
        aBoard[row][column][plane].value = is.get();
        aBoard[row][column][plane].x = x[plane] + column;
        aBoard[row][column][plane].y = y[plane] + row;
      }

      x[plane]--;
    }
  }

  is.close();

  int nIndex = 0;
  for (int i = -1; i <= 1; i++)
  {
    for (int j = -1; j <= 1; j++)
    {
      for (int k = -1; k <= 1; k++)
      {
        aMoves[nIndex].row = i;
        aMoves[nIndex].column = j;
        aMoves[nIndex].plane = k;
        nIndex++;
      }
    }
  }
}

void printArray()
{
  for (int plane = 0; plane <= 4; plane++)
  {
    for (int row = 0; row <= 9; row++)
    {
      for (int column = 0; column <= 9; column++)
      {
        objScreen.printChar(aBoard[row][column][plane].value,
                            aBoard[row][column][plane].x,
                            aBoard[row][column][plane].y);
      }
    }
  }
}

bool validMove (int row, int column, int plane)
{
  if ((row >= 0 && row <= 9)
      && (column >= 0 && column <= 9)
      && (plane >= 0 && plane <= 4)
      && (aBoard[row][column][plane].value == ' '))
  {
    return true;
  }
  else
  {
    return false;
  }
}

bool mazeSolved()
{
  if (aBoard[9][9][4].value == '-')
  {
    return true;
  }
  else
  {
    return false;
  }
}

bool mazeMe(int row, int column, int plane)
{
  int nNewRow, nNewColumn, nNewPlane = 0;

  if (not mazeSolved())
  {
    for (int nIndex = 0; nIndex <= 26; nIndex++)
    {
      nNewRow = row + aMoves[nIndex].row;
      nNewColumn = column + aMoves[nIndex].column;
      nNewPlane = plane + aMoves[nIndex].plane;

      if (validMove(nNewRow, nNewColumn, nNewPlane))
      {
        aBoard[nNewRow][nNewColumn][nNewPlane].value = '-';
        objScreen.printChar('-',
                            aBoard[nNewRow][nNewColumn][nNewPlane].x,
                            aBoard[nNewRow][nNewColumn][nNewPlane].y,
                            32, 0, 0, 25000);

        if (not mazeMe(nNewRow, nNewColumn, nNewPlane))
        {
          aBoard[nNewRow][nNewColumn][nNewPlane].value = 'B';
          objScreen.printChar('B',
                              aBoard[nNewRow][nNewColumn][nNewPlane].x,
                              aBoard[nNewRow][nNewColumn][nNewPlane].y,
                              31, 0, 0, 25000);
        }
else	nIndex = 27;
      }
    }
  }

  return mazeSolved();
}

int main()
{
  bool bCont = true;
  char strCont = 'y';

  while (bCont)
  {
    objScreen.clearScreen();
    objScreen.moveCursor(0, 0);
    cout.flush();

    buildArray();
    printArray();

    if (aBoard[0][0][0].value == ' ')
    {
      objScreen.printChar('-', aBoard[0][0][0].x, aBoard[0][0][0].y, 32, 0, 0, 25000);
    }

    if (mazeMe(0, 0, 0))
    {
      objScreen.printString("Puzzled Solved!", 1, 22, 25000);
    }
    else
    {
      objScreen.printString("Puzzle Unsolvable.", 1, 22, 25000);
    }

    objScreen.moveCursor(0, 23);
    cout.flush();

    objScreen.printString("Do you want to play another game (y/n)? ", 1, 23, 25000);
    cin >> strCont;

    if (strCont == 'n')
    {
      bCont = false;
    }

    cin.ignore(1000, '\n');
  }

  return 0;
}
