#include <iostream>

using namespace std;

class CLinkedList
{
  public:
    // Default Constructor
    CLinkedList ();

    // Deconstructor
    ~CLinkedList ();

    // Copy Constructor
    // CLinkedList *copyList ();

    // Append Data
    void appendData (float nInput);

    // Search List
    bool searchList (float nInput);

    // Remove Data
    void removeData (float nInput);

    // Length of List
    int length ();

    // Retrieve by Number
    float retrieveByNumber (int nNumber);

    // Copy List
    CLinkedList copyList (CLinkedList ptrInput);

  private:
    struct hNode
    {
      float data;
      hNode *next;
    } *ptrHead;
};
