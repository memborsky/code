from collections import Counter
import string

example1 = "This is a string we are using as an example to show number of occurances. For every word in the string, lets count the number of times it is present in the string. After we do that, return the result with the most occurances. This result should be the same result every time we call the function, no matter the compiler."
example2 = "oF The of the of the of. th.e the o!f .The! of*"

def strip_punctuation(s):
    for c in string.punctuation:
        s = s.replace(c, ' ')

    return s

def count_occurances(s):
    return Counter(s.split())

print count_occurances(strip_punctuation(example1).lower()).most_common(1)
print count_occurances(strip_punctuation(example2).lower()).most_common(1)
