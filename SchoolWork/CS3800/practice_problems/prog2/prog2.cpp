#include <iostream>

using namespace std;

int main ()
{
  int nInputValue1 = 0;
  int nInputValue2 = 0;
  int nInputValue3 = 0;
  int nSumOfValues = 0;
  int nProductOfValues = 0;

  // Begin input.
  cout << "Please input your first integer value - ";
  cin >> nInputValue1;

  cout << "Please input your second integer value - ";
  cin >> nInputValue2;

  cout << "Please input your third integer value - ";
  cin >> nInputValue3;
  // End input.

  // Begin calculations
  nSumOfValues = nInputValue1 + nInputValue2 + nInputValue3;
  nProductOfValues = nInputValue1 * nInputValue2 * nInputValue3;
  // End calculations

  // Begin output.
  cout << endl;
  cout << nInputValue1 << " + " << nInputValue2 << " + " << nInputValue3 << " = " << nSumOfValues << endl;
  cout << nInputValue1 << " * " << nInputValue2 << " * " << nInputValue3 << " = " << nProductOfValues << endl;
  // End output.
}
