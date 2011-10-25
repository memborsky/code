#include <iostream>
#include <fstream>
#include <string>

using namespace std;

int countWord(string strHaystack, string strNeedle)
{
  int nHayEnd   = strHaystack.length() - 1;
  int nCurrent  = 0;
  int nCount    = 0;
  string::size_type nPosition = 0;

  while (nCurrent <= nHayEnd)
  {
    nPosition = strHaystack.find(strNeedle, nCurrent);

    if (nPosition != string::npos)
    {
      nCount++;
      nCurrent = nPosition + 1;
    }
    else
    {
      nCurrent = nHayEnd + 1;
    }
  }

  return nCount;
}

int main ()
{
  ifstream hFile;

  bool bStop          = false;

  string strFilename  = "stop";
  string strLine      = "";
  string strSearch    = "";

  int nLineCount      = 0;
  int nSearchCount    = 0;

  char strNull;

  while (!bStop)
  {
    /* Grab the file name. */
    cout << "Enter File Name (or stop): " << endl;
    cin >> strFilename;

    /* Reset our variables */
    strSearch     = "";
    strLine       = "";
    nSearchCount  = 0;
    nLineCount    = 0;

    /* Open the file */
    hFile.open(strFilename.c_str());

    /* If the file is not open, then we don't have a valid file name. */
    if (hFile.is_open())
    {
      /* We have a valid file and lets continue. */
      cout << "Enter search term." << endl;
      cin >> strSearch;

      /* Continue while we are not at the end of the file. */
      while (!hFile.eof())
      {
        strLine = "";
        getline (hFile, strLine);
        nLineCount++;

        if (strLine != "")
        {
          nSearchCount += countWord(strLine, strSearch);
        }
      }

      cout << "The file \"" << strFilename << "\" has " << nLineCount << " lines." << endl;
      cout << "The string \"" << strSearch << "\" occurs " << nSearchCount << " times." << endl;
      hFile.close();
    }
    else
    {
      if (strFilename != "stop")
      {
        /* Invalid File name has been entered */
        cout << "File name: I/O error" << endl;
      }
      else
      {
        cout << "Program stopped" << endl;
        bStop = true;
      }
    }
  }
}
