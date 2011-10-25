With Ada.Text_IO;

Procedure game Is

start : string(1..30);
length : integer;

Begin

  start(1..4) := "asdf";
  Loop
    Ada.Text_IO.put("Do you want to create a new game? ");
    Ada.Text_IO.get_line(start, length);
    Exit When start(1..3) = "die";
    Ada.Text_IO.put_line("YOU SUCK!");
  End Loop;

End game;
