----------------------------------------------------------------
--| Assignment 1, initals program
--| Matt Emborsky
--| CS1305AA
--| Due Date: Friday 2/6/2004
----------------------------------------------------------------
----------------------------------------------------------------
--| This program will print out my initals in BIG BLOCK formation
--| using the letter of that inital.  M L E will be the letters used
--|
--| Read the initals.readme file for more information on the files that are contained
--| in this program and the steps to completing the program.
--|
--| Author: Matt Emborsky, Student at Indiana Institute of Technology
--| January 29, 2004
----------------------------------------------------------------

With Ada.Text_IO;

Procedure initials is

Begin  --Beginning of program

  Ada.Text_IO.Put_Line ("M         M    LL           EEEEEEEE"); --This line is the top line of the Initals
  Ada.Text_IO.Put_Line ("MM       MM    LL           EEEEEEE");
  Ada.Text_IO.Put_Line ("MMM     MMM    LL           EE");
  Ada.Text_IO.Put_Line ("MM M   M MM    LL           EEEEE");
  Ada.Text_IO.Put_Line ("MM  M M  MM    LL           EEEEE");
  Ada.Text_IO.Put_Line ("MM   M   MM    LL           EE");
  Ada.Text_IO.Put_Line ("MM       MM    LLLLLLLL     EEEEEEE");
  Ada.Text_IO.Put_Line ("MM       MM    LLLLLLLLL    EEEEEEEE"); --This line is the last line of the Initals

End initials; --End of program
