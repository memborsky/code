#include <iostream>
#include <ctype.h>

class CBinaryTree
{
  public:
    // Allows pointer access to tree struct.
    struct treeStruct;

    // Default Constructor
    CBinaryTree ();

    // Construct a new node with input data and null pointers.
    treeStruct newNode (char strInput);

  private:
    struct treeStruct
    {
      char item;
      treeStruct *left;
      treeStruct *right;
    };
};
