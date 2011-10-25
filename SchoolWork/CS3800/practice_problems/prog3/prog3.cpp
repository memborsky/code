#include <iostream>
#include <iomanip>

using namespace std;

int main ()
{
  float nInputValue1 = 0.0;
  float nInputValue2 = 0.0;
  float nRatioOfValues = 0.0;

  // Being input
  cout << "Please input first floating point value - ";
  cin >> nInputValue1;

  cout << "Please input second floating point value - ";
  cin >> nInputValue2;
  // End input

  // Begin calculations
  nRatioOfValues = nInputValue1 / nInputValue2;
  // End calculations

  // Begin output
  cout << endl;
  cout << nInputValue1 << " / " << nInputValue2 << " = " << nRatioOfValues << endl;
  cout << fixed;
  cout << setprecision(2) << nInputValue1 << endl << nInputValue2 << endl;
  cout << setprecision(3) << nRatioOfValues << endl;
  // End output
}
