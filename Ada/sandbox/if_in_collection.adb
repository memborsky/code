With Ada.Text_IO;
With Ada.Characters.Handling;

Procedure if_in_collection is

  last  : Integer;
  input : String(1..10);

Begin

  Ada.Text_IO.Put_Line("Enter string 10 chars or less - ");
  Ada.Text_IO.Get_Line(input, last);

  Ada.Text_IO.Put_Line(Ada.Characters.Handling.To_Upper(input));

  If Ada.Characters.Handling.To_Upper(input) In ["HELLO", "WORLD", "GOOD", "STUFF"] Then
    Ada.Text_IO.Put_Line("Worked - " & input);
  Else
    Ada.Text_IO.Put_Line("No Worky - " & input);
  End If;

End;
