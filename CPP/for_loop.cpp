#include <iostream>

using namespace std;

int main ()
{
  int max = 10;

  for (int i = 0; i < max; i++)
  {
    cout << "Count: " << i << "; " << max << endl;
    max--;
    i++;
  }
}
