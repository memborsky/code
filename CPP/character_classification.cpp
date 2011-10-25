#include <iostream>
#include <ctype.h>

using namespace std;

int main()
{
  char strTest;

  strTest = '$';

  if (isalnum(strTest))
  {
    cout << "true" << endl;
  }
  else
  {
    cout << "false" << endl;
  }

}
