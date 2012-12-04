example = "Hello {name}, you are {age} old."
name = "Matt"
age = 27

# Replace {name} with variable name.
print example.replace("{name}", name).replace("{age}", str(age).zfill(4))
