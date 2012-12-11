import os

print "Basename: " + os.path.basename(__file__)
print "Directory Name: " + os.path.abspath(os.path.dirname(__file__))
print "Real Path: " + os.path.realpath(__file__)
print "Current Directory: " + os.curdir
print "Parent Directory: " + os.path.abspath(os.path.join(os.getcwd(), os.path.pardir))
