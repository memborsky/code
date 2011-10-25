----------------------------------------------------------------
--| Assignment 0, Copy Program
--| Matt Emborsky
--| CS1305AA
--| Due Date: Friday 1/30/2004
----------------------------------------------------------------
----------------------------------------------------------------
--| ThIs example program finds and prints the names of three
--| days given today's name. It prints yesterday, today and
--| tomorrow in that order when today's name Is entered
--|
--| Author: Michael Feldman, The George Washington University
--| Last ModIfied by Martin Mansfield, Indiana Tech
--|
--|                   January 2004
----------------------------------------------------------------

With Ada.Text_IO; -- use the standard text I/O package

Procedure three_days Is
  -- the name "three_days" should match the file name "three_days.adb"

  -- create a data type that defines the days of the week
  Type Days Is (Sunday, Monday, Tuesday, Wednesday, Thursday,
                Friday, Saturday);

  -- instantiate a package of I/O functions for the data type Days
  Package Day_IO Is New Ada.Text_IO.Enumeration_IO (Enum => Days);

  -- declare variables for the days to be named
  Yesterday : Days;
  Today     : Days;
  Tomorrow  : Days;

Begin -- three_days: thIs Is where the executable part begins

  -- prompt user to enter the name of a day
  Ada.Text_IO.Put(Item => "Enter the name of a day of the week > ");
  -- get the day using the Days I/O package
  Day_IO.Get (Item => Today);

  -- find yesterday
  If Today = Days'First Then -- Today Is Sunday, the first day
    Yesterday := Days'Last; -- so, Yesterday Is Saturday, the last day
  Else -- Today Is not the first day
    Yesterday := Days'Pred(Today); --so, Yesterday Is the previous day
  End If;

  -- print Yesterday with a label
  Ada.Text_IO.Put (Item => "Yesterday was ");
  Day_IO.Put (Item => Yesterday);
  Ada.Text_IO.New_Line; -- skip to next output line

  -- print Today  with a label
  Ada.Text_IO.Put (Item => "Today is ");
  Day_IO.Put (Item => Today);
  Ada.Text_IO.New_Line; -- skip to next line

  -- find tomorrow
  If Today = Days'Last Then -- Today Is Saturday, the last day
    Tomorrow := Days'First; -- so, Tomorrow Is Sunday, the first day
  Else -- Today Is not the last day
    Tomorrow := Days'Succ(Today); --so, Tomorrow Is the succeeding day
  End If;

  -- print Tomorrow
  Ada.Text_IO.Put (Item => "Tomorrow is ");
  Day_IO.Put (Item => Tomorrow);
  Ada.Text_IO.New_Line;  -- skip to next line

End Three_Days; -- Ends the program
