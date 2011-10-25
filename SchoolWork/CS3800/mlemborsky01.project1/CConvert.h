#include <iostream>
#include <string>
#include <math.h> /* Used to call fabs which is an abs function */

using namespace std;

class CConvert
{
  public:
    /* Null Constructor */
    CConvert ();

    /* Convert an unsigned integer string in any base to its decimal (internal) value. */
    int strToInt (int nBase, string strInput);

    /* Convert an unsigned real string in any base to its decimal (internal) value. */
    float strToReal (int nBase, string strInput);

    /* Convert a signed real string in any base to its decimal value. */
    float signedStrToReal (int nBase, string strInput);

    /* Convert a decimal fraction to its string representation in any base. */
    string fractionToStr (int nBase, int nLimit, float nValue);

    /* Convert a positive decimal real number to its string representation in any base. */
    string posRealToStr (int nBase, int nLimit, float nValue);

    /* Convert any (signed or unsigned) real value to its string representation */
    string realToStr (int nBase, int nLimit, float nValue);
};
