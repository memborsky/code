def number(num):
  """ Returns a number """
  return num

print [n / 2 for n in number([1, 2, 3, 4, 5, 6, 7])]

print [number(n) / 2 for n in [1, 2, 3, 4, 5, 6, 7]]