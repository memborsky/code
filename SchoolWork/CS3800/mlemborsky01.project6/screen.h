#include <string>

using namespace std;

class screen
{
  public:
    // Default constructor.
    screen();

    // Clear the entire screen of text.
    void clearScreen();
    
    // Move the cursor to x, y.
    void moveCursor(int nX, int nY);

    // Print a string starting at x and going until the end of the string has been met.
    void printString(string strInput, int nStartX, int nStartY, int nSleep, bool bReverse = false);

    // Print a single character with more options than printString.
    void printChar(char strInput, int nX, int nY,
                   int nFGColor = 0, int nBGColor = 0, int nAttribute = 0, int nSleep = 0);
};
