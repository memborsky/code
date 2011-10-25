#include <iostream>
#include <fstream>

using namespace std;

int main()
{
  char c, str[256];
  ifstream is;

  cout << "Input file name: ";
  cin.get(str, 256);

  is.open(str);

  while (is.good())
  {
    c = is.get();
    cout << c;
  }

  is.close();

  return 0;
}
