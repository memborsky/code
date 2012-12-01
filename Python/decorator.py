# Example below is from:
# http://stackoverflow.com/questions/739654/understanding-python-decorators

# The decorator to make it bold
def makebold(func):

    # The new function the decorator returns
    def wrapper(arg1, arg2):
        # Insertion of some code before and after
        return "<strong>" + func(arg1, arg2) + "</strong>"

    return wrapper

# The decorator to make it italic
def makeitalic(func):

    # The new function the decorator returns
    def wrapper(arg1, arg2):
        # Insertion of some code before and after
        return "<em>" + func(arg1, arg2) + "</em>"

    return wrapper

@makebold
@makeitalic
def say(phrase, name):
    return phrase + name + "!"

print say("Hello ", "Matt")
#outputs: <strong><em>Hello World!</em></strong>