#include "CBinaryTree.h"

CBinaryTree::CBinaryTree ()
{

}

CBinaryTree::treeStruct CBinaryTree::newNode (char strInput)
{
  treeStruct treeNode;
  treeNode.item   = strInput;
  treeNode.left   = NULL;
  treeNode.right  = NULL;

  return treeNode;
}

CBinaryTree::treeStruct 
