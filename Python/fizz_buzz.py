def fizz_buzz (num):
    fizz = num % 3 == 0
    buzz = num % 5 == 0

    if fizz and buzz:
        print "FizzBuzz"
    elif fizz:
        print "Fizz"
    elif buzz:
        print "Buzz"
    else:
        print num

[fizz_buzz(n) for n in range(1, 101)]
