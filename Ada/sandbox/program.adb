With Ada.text_IO;
With Ada.Integer_Text_IO; Use Ada.Integer_Text_IO;

Procedure program is

  month, day, year : Integer := 0;

Begin

  Ada.Text_IO.put("Please input a date in the form MM DD YYYY (1901 < YYYY > 1999) - ");
  Get(month);
  Get(day);
  Get(year);

  while (year < 1901) OR (year > 1999) loop

    Ada.Text_IO.put("Invalid year, please input a new year between 1901 and 1999 - ");
    Get(year);

  end loop;

  Ada.Text_IO.Put_Line("You have input the following information:");
  Ada.Text_IO.Put_Line("Year - " & Integer'Image(year) & " Month - " & Integer'Image(month) & " Day - " & Integer'Image(day));

End program;
