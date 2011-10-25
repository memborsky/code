#include "CConvert.h"
#include <sstream> /* Used to convert Integer data types to String data types. */

/* Null Constructor */
CConvert::CConvert ()
{
  /* Do Nothing */
}

/* Convert an unsigned integer string in any base to its decimal (internal) value. */
int CConvert::strToInt (int nBase, string strInput)
{
  int nValue = 0;
  char strSymbol;

  for (int nCurrent = 0; nCurrent <= strInput.length() - 1; nCurrent++)
  {
    strSymbol = strInput[nCurrent];
    nValue = nValue * nBase + int(strSymbol);
  }

  return nValue;
}

/* Convert an unsigned real string in any base to its decimal (internal) value. */
float CConvert::strToReal(int nBase, string strInput)
{
  float nValue = 0.0;
  int nShift = 0;
  bool bFoundPoint = false;
  char strSymbol;

  while (strInput != "")
  {
    strSymbol = strInput[0];

    if (strSymbol == '.')
    {
      bFoundPoint = true;
    }
    else
    {
      nValue = nValue * nBase + (int)strSymbol;

      if (bFoundPoint)
      {
        nShift = nShift + 1;
      }
    }

    strInput = strInput.erase(0, 1);
  }

  while (nShift != 0)
  {
    nValue = nValue / nBase;
    nShift = nShift - 1;
  }

  return nValue;
}

/* Convert a signed real string in any base to its decimal value. */
float CConvert::signedStrToReal(int nBase, string strInput)
{
  char strSymbol;
  float nValue;

  strSymbol = strInput[0];

  if (strInput[0] == '-')
  {
    nValue = strToReal(nBase, strInput.substr(1, strInput.length())) * -1;
  }
  else
  {
    if (strInput[0] == '+')
    {
      nValue = strToReal(nBase, strInput.substr(1, strInput.length()));
    }
    else
    {
      nValue = strToReal(nBase, strInput);
    }
  }
}

/* Convert a decimal fraction to its string representation in any base. */
string CConvert::fractionToStr(int nBase, int nLimit, float nValue)
{
  string strReturn = ".";
  char strSymbol;

  for (int nIndex = 0; nIndex <= nLimit; nIndex++)
  {
    nValue = nValue * nBase;
    strSymbol = char(nValue);
    strReturn = strReturn + strSymbol;
    nValue = nValue - (int)nValue;
  }

  return strReturn;
}

/* Convert a positive decimal real number to its string representation in any base. */
string CConvert::posRealToStr (int nBase, int nLimit, float nValue)
{
  string strReturn = "";
  stringstream strSymbol;
  int nShift = 0;

  while (nValue >= 1)
  {
    nShift = nShift + 1;
    nValue = nValue / nBase;
  }

  while (nShift != 0)
  {
    nValue = nValue * nBase;

    /* Convert the integer to a string. */
    strSymbol << int(nValue);

    strReturn.append(strSymbol.str());
    nValue = nValue - int(nValue);
    nShift = nShift - 1;

    /* Drop the recent integer to string conversion literal. */
    strSymbol.str("");
  }

  strReturn = strReturn + fractionToStr(nBase, nLimit, nValue);
  return strReturn;
}

/* Convert any (signed or unsigned) real value to its string representation */
string CConvert::realToStr (int nBase, int nLimit, float nValue)
{
  string strReturn;

  if (nValue < 0.0)
  {
    /* fabs = abs */
    strReturn = strReturn + posRealToStr(nBase, nLimit, fabs(nValue));
  }
  else
  {
    strReturn = posRealToStr(nBase, nLimit, nValue);
  }

  return strReturn;
}
