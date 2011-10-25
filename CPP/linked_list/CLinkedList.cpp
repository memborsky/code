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

/* CLinkedList *CLinkedList::copyList ()
{
  return *ptrHead;
} */

void CLinkedList::appendData (float nInput)
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
    ptrNew->next = NULL;
    ptrFindEnd->next = ptrNew;
  }
}

bool CLinkedList::searchList (float nInput)
{
  hNode *ptrFind;

  if (ptrHead == NULL)
  {
    return false;
  }
  else
  {
    ptrFind = ptrHead;

    while (ptrFind->data != nInput || ptrFind->next != NULL)
    {
      ptrFind = ptrFind->next;
    }

    if (ptrFind->data == nInput)
    {
      return true;
    }
    else
    {
      if (ptrFind->next == NULL)
      {
        return false;
      }
    }
  }

  return false;
}

void CLinkedList::removeData (float nInput)
{
  hNode *ptrCurrent;
  hNode *ptrPrevious;

  ptrCurrent = ptrHead;

  if (ptrCurrent->data == nInput)
  {
    ptrHead = ptrCurrent->next;
    delete ptrCurrent;
    return;
  }

  ptrPrevious = ptrCurrent;

  while (ptrCurrent != NULL)
  {
    if (ptrCurrent->data == nInput)
    {
      ptrPrevious->next = ptrCurrent->next;
      delete ptrCurrent;
      return;
    }

    ptrPrevious = ptrCurrent;
    ptrCurrent = ptrCurrent->next;
  }
}

int CLinkedList::length ()
{
  hNode *ptrCount;
  int nCount = 0;

  for (ptrCount = ptrHead; ptrCount != NULL; ptrCount = ptrCount->next)
  {
    nCount++;
  }

  return nCount;
}

float CLinkedList::retrieveByNumber (int nNumber)
{
  int nCount = 0;
  hNode *ptrReturn;

  ptrReturn = ptrHead;

  if (ptrReturn == NULL)
  {
    cout << "WARNING: List was empty when trying to retrieveByNumber." << endl;
    return -1.0;
  }

  while (nCount < nNumber && ptrReturn != NULL)
  {
    nCount++;
    ptrReturn = ptrReturn->next;
  }

  if (nCount != nNumber && ptrReturn == NULL)
  {
    cout << "WARNING: List was accessed out of size." << endl;
    return -1.0;
  }

  return ptrReturn->data;
}
