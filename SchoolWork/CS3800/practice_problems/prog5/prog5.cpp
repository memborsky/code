#include <iostream>
#include <string>

using namespace std;

//
// Functions:
//

// Takes a string input and returns it in full capitol letters.
//
// Parameter(s):
// -> sInput: string
//
// Return
// <- string
string lowerToUpper(string sInput)
{
  // Setup local variables.
  string sResult  = "";
  int nIndex      = 0;

  // Loop through the length of the string and convert it to its upper case clone.
  for (nIndex = 0; nIndex <= sInput.length() - 1; nIndex++)
  {
    // Checking to see if the character is in lower case.
    if ((int)sInput[nIndex] >= 97 && (int)sInput[nIndex] <= 122)
    {
      sResult += (char)((int)sInput[nIndex] - 32);
    }
    else
    {
      sResult += sInput[nIndex];
    }
  }

  // Return our newly created string.
  return sResult;
}

// Takes a string and strips all non-capitol characters for checking if it is a palidrome.
//
// Parameter(s):
// -> sInput: string
//
// Return
// <- string
string stripString (string sInput)
{
  // Setup local variables.
  string sResult  = "";
  int nIndex      = 0;

  for (nIndex = 0; nIndex <= sInput.length() - 1; nIndex++)
  {
    if ((int)sInput[nIndex] >= 65 && (int)sInput[nIndex] <= 90)
    {
      sResult += sInput[nIndex];
    }
  }

  return sResult;
}

// Checks to see if the string is a Palidrome or not.
//
// Parameter(s):
// -> sInput: string
//
// Return
// <- boolean (True if a Palidrome; False if NOT a Palidrome)
bool isPalindrome (string sInput)
{
  // Grab our stripped string and store it in a local variable.
  string sCheck = stripString(sInput);

  // Setup local variables.
  bool bResult        = true;
  bool bQuit          = false;
  int nIndexForwards  = 0;
  int nIndexBackwards = sCheck.length() - 1;

  // While we are not in need of quiting, continue looping.
  while (bQuit == false)
  {
    // Check to see if the character going forwards is the same as the character going backwards.
    if (sCheck[nIndexForwards] != sCheck[nIndexBackwards])
    {
      bResult = false;
      bQuit   = true;
    }

    // If we check the backwards index and it is 0, then we are at the end of the string.
    // This means that we can quit the while loop.
    if (nIndexBackwards < nIndexForwards)
    {
      bQuit   = true;
    }

    // Increment our counters.
    nIndexForwards++;
    nIndexBackwards--;
  }

  // Return our result.
  return bResult;
}

//
// MAIN ENTRY
//
int main ()
{
  // Setup local variables.
  string sInput = "";
  string sUpper = "";

  // Being input
  cout << "Please input a string - ";
  getline (cin, sInput);
  cout << endl;
  // End input

  // Convert the string entered into an upper case string.
  sUpper  = lowerToUpper(sInput);

  // Being Output
  if (isPalindrome(sUpper))
  {
    cout << sUpper << " - is a palindrome." << endl;
  }
  else
  {
    cout << sUpper << " - is NOT a palindrome." << endl;
  }
  // End Output
}
