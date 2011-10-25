#include <iostream>
#include <stdio.h>
#include <stdlib.h>
#include <time.h>
#include "CLinkedList.h"

using namespace std;

CLinkedList hList;

void initialize (int nInput)
{
  int nIndex = 1;

  for (nIndex = 1; nIndex <= nInput; nIndex++)
  {
    if (nIndex % 10 == 0)
    {
      cout << ".";
    }

    hList.add(rand() % 100);
  }
}

void printList ()
{
  int nLength = hList.length();
  int nIndex = 0;

  for (nIndex = 0; nIndex <= nLength; nIndex++)
  {
    if (nIndex % 10 == 0)
    {
      cout << endl;
    }

    if (hList.retrieveByNumber(nIndex) > 9)
    {
      cout << hList.retrieveByNumber(nIndex) << "  ";
    }
    else
    {
      cout << hList.retrieveByNumber(nIndex) << "   ";
    }
  }
}

void remove(int nInput)
{
  int nIndex = 0;
  int nLength = hList.length();

  if (nInput <= nLength)
  {

    for (nIndex = 1; nIndex <= nInput; nIndex++)
    {
      hList.del(hList.retrieveByNumber(1));
    }

  }

  if (hList.countRemoved() > (hList.length() / 2))
  {
    hList.dump();
  }
}

int main ()
{
  CLinkedList hList;
  int nCreate = 30;
  int nRemove = 0;
  srand(time(NULL));

  cout << "Please input how many items you want in the list - ";
  cin >> nCreate;
  cout << endl;

  cout << "Please input how many items you want to remove from the list - ";
  cin >> nRemove;
  cout << endl;

  cout << "Initlizing list ";
  initialize(nCreate);

  cout << endl << "Printing initialized list:" << endl;
  printList();
  cout << endl;

  remove(nRemove);
  cout << "Priting list after removal:" << endl;
  printList();
  cout << endl;

  cout << "Printing list after deletion dump:" << endl;
  printList();
  cout << endl;

  return 0;
}
