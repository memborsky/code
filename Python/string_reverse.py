example = "This is a test string."

# String exploded on a (space)
spliced = example.split(" ")

# Print the original string in a complete reverse order.
print example[::-1]

# Reverse the spliced string and print it back as a whole string
spliced.reverse()
print " ".join(spliced)
