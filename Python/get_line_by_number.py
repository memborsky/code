#!/usr/bin/python
import os, sys

path = sys.argv[1]
line_number = int(sys.argv[2])

def walk_tree(path):

    for filename in os.listdir(path):
        if os.path.isdir(os.path.join(path, filename)):
            if filename not in ["vim", ".git"]:
                walk_tree(os.path.join(path, filename))

        else:
            extension = os.path.splitext(filename)[1]

            if extension not in [".symlink", ".pyc"]:
                full_file   = os.path.join(path, filename)
                fp          = open(full_file)
                index       = 1

                for line in fp:
                    if index < line_number:
                        index += 1
                    elif index == line_number:
                        print full_file + ": " + str(line)
                        break

                fp.close()

walk_tree(path)
