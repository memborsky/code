#include "CLinkedList.h"
#include <iostream>
#include <ctime>
#include <cstdlib>

int main ()
{
  CLinkedList hList1;
  srand((unsigned)time(0));

  cout << "Creating first and filling with data ..." << endl;
  for (int index = 0; index <= 30; index++)
  {
    hList1.appendData(rand());
  }

  cout << "List 1 - " << endl;
  for (int index = 0; index <= hList1.length(); index++)
  {
    cout << "hList1[" << index << "] = " << hList1.retrieveByNumber(index) << endl;
  }

  cout << "Creating second list and filling it with data from the first list ..." << endl;
  CLinkedList hList2;
  int nList1Length = hList1.length();
  for (int index = 0; index <= nList1Length; index++)
  {
    hList2.appendData(hList1.retrieveByNumber(0));
    hList1.removeData(hList1.retrieveByNumber(0));
  }

  cout << "List 1 - " << endl;
  if (hList1.length() == 0)
  {
    cout << "List Empty" << endl;
  }
  else
  {
    for (int index = 0; index <= hList1.length(); index++)
    {
      cout << "hList1[" << index << "] = " << hList1.retrieveByNumber(index) << endl;
    }
  }

  cout << endl << "List 2 - " << endl;
  for (int index = 0; index <= hList2.length(); index++)
  {
    cout << "hList2[" << index << "] = " << hList2.retrieveByNumber(index) << endl;
  }

  return 0;
}
