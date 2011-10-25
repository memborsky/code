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

    // Add Data
    bool add (int nInput);

    // Remove Data
    void del (int nInput);

    // Length of list
    int length ();

    // Get the nInput"th" element from the list.
    int retrieveByNumber (int nInput);

    // Count the number of removable items
    int countRemoved ();

    // Dump removable items from list
    void dump ();

  private:
    struct hNode
    {
      int data;
      bool remove;
      hNode *next;
    } *ptrHead;
};
