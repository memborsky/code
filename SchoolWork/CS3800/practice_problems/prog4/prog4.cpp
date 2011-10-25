#include <iostream>
#include <math.h>

using namespace std;

int fibonacci (int nInput)
{
  int nPrevious1, nPrevious2, nPrevious3 = 0;
  int nReturn = 0;
  int nIndex = 3;

  switch (nInput)
  {
    case 1:
    case 2:
      nReturn = 1;
      break;

    default:
      nPrevious1 = 1;
      nPrevious2 = 1;

      for (nIndex = 3; nIndex <= nInput; nIndex++)
      {
        nPrevious3 = nPrevious2 + nPrevious1;
        nPrevious1 = nPrevious2;
        nPrevious2 = nPrevious3;
      }

      nReturn = nPrevious3;
  }

  return nReturn;
}

int raisePower (int nInput)
{
  return pow(2, nInput);
}

int factorial (int nInput)
{
  int nResult = 1;

  for (int nIndex = 1; nIndex <= nInput; nIndex++)
  {

    nResult = nResult * nIndex;

  }

  return nResult;
}

int main ()
{
  int nInputValue = 0;

  // Begin input
  cout << "Please input an integer value - ";
  cin >> nInputValue;
  // End input

  // Begin output
  cout << endl;
  cout << "Fibonacci Number - " << fibonacci(nInputValue) << endl;
  cout << "2^n - " << raisePower(nInputValue) << endl;
  cout << "n! - " << factorial(nInputValue) << endl;
  // End output
}
