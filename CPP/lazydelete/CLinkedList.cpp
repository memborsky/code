#include "CLinkedList.h"

CLinkedList::CLinkedList ()
{
  ptrHead = NULL;
}

CLinkedList::~CLinkedList ()
{
  hNode *ptrDelete;

  if (ptrHead == NULL)
  {
    return;
  }

  while (ptrHead != NULL)
  {
    ptrDelete = ptrHead->next;
    delete ptrHead;
    ptrHead = ptrDelete;
  }
}

bool CLinkedList::add (int nInput)
{
  hNode *ptrFindEnd;
  hNode *ptrNew;

  if (ptrHead == NULL)
  {
    ptrHead = new hNode;
    ptrHead->data = nInput;
    ptrHead->next = NULL;
  }
  else
  {
    ptrFindEnd = ptrHead;
    while (ptrFindEnd->next != NULL)
    {
      ptrFindEnd = ptrFindEnd->next;
    }

    ptrNew = new hNode;
    ptrNew->data = nInput;
    ptrNew->remove = false;
    ptrNew->next = NULL;
    ptrFindEnd->next = ptrNew;
  }
}

int CLinkedList::countRemoved ()
{
  int nFlagged = 0;
  hNode *ptrCurrent;

  ptrCurrent = ptrHead;

  while (ptrCurrent != NULL)
  {

    if (ptrCurrent->remove)
    {
      nFlagged++;
    }

    ptrCurrent = ptrCurrent->next;
  }

  return nFlagged;
}

int CLinkedList::length ()
{
  hNode *ptrCount;
  int nCount = 0;

  for (ptrCount = ptrHead; ptrCount != NULL; ptrCount = ptrCount->next)
  {
    nCount++;
  }

  return nCount - 1;
}

// Dump our nodes that are flagged for removal.
void CLinkedList::dump ()
{
  hNode *ptrCurrent = ptrHead;
  hNode *ptrPrevious = ptrHead;

  if (ptrCurrent->remove)
  {
    // Reset the head pointer so that we can recall this procedure without a lost pointer.
    ptrHead = ptrCurrent->next;

    // Delete the node.
    delete ptrCurrent;

    // Restart the dump process.
    dump ();
  }

  while (ptrCurrent->next != NULL)
  {

    if (ptrCurrent->remove)
    {
      // Rework the list.
      ptrPrevious->next = ptrCurrent->next;

      // Delete the node.
      delete ptrCurrent;

      // Setup for the loop to continue working.
      ptrCurrent = ptrPrevious;
    }

    ptrPrevious = ptrCurrent;
    ptrCurrent = ptrCurrent->next;
  }

  if (ptrCurrent->remove)
  {
    // Set the end of the list to NULL.
    ptrPrevious->next = NULL;

    // Remove the last node in the list.
    delete ptrCurrent;
  }

}

// Flag our item for removal.
void CLinkedList::del (int nInput)
{
  hNode *ptrCurrent = ptrHead;

  while (ptrCurrent != NULL)
  {

    if (ptrCurrent->data == nInput)
    {
      ptrCurrent->remove = true;
      break;
    }

    ptrCurrent = ptrCurrent->next;
  }

  if (countRemoved() > (length() / 2))
  {
    dump();
  }
}

int CLinkedList::retrieveByNumber (int nInput)
{
  hNode *ptrCurrent;
  int nIndex = 1;

  ptrCurrent = ptrHead;

  for (nIndex = 1; nIndex <= nInput; nIndex++)
  {
    ptrCurrent = ptrCurrent->next;
  }

  return ptrCurrent->data;
}
