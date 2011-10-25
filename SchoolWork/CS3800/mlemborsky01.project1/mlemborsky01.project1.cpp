#include <iostream>
#include "CConvert.h"
#include <string>

int main ()
{
  CConvert objConverter;

  int nBase1;
  int nBase2;
  string strInput;
  string strOutput;
  float nValue;

  cout << "Please input data in this form: base1 string base2 - ";
  cin >> nBase1;
  cin >> strInput;
  cin >> nBase2;

  nValue = objConverter.strToReal(nBase1, strInput);

  cout << "Input:   " << strInput << "    Base: " << nBase1 << "    Value: " << nValue << endl;

  strOutput = objConverter.realToStr(nBase2, 4, nValue);
  nValue = objConverter.strToReal(nBase2, strOutput);

  cout << "Output:  " << strOutput << "   Base: " << nBase2 << "    Value: " << nValue << endl;

  return 0;
}
