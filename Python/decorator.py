def divide_by_two(func):
    """ Always divide by 2 """

    def wrapper(*args, **kargs):
        return func(*args, **kargs) / 2

    return wrapper

@divide_by_two
def number(num):
    """ Simply divide whatever we input by 2 [our decorator function]. """
    return num

print number(10)
#outputs: 5