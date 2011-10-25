def sum(n):
  """ Return sum in our alogorithm """
  partialSum = 0;

  for i in range(1,n):
    partialSum = partialSum + i * i * i

  return partialSum

nSum = sum(13)
print nSum
