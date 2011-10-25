With Ada.Text_IO;
With Ada.Integer_Text_IO;
With Ada.Strings.Unbounded;
With Ada.Command_Line;
With Parse;

Procedure assemble Is

  Package Unbounded Renames Ada.Strings.Unbounded;
  Package parser is New Parse (Label_Seperator => ':');

  Type tLines is Array (1..Parser.Size_Of_File(Unbounded.To_String(Parser.Command_Line))) of Parser.Line;
--        While index <= sString'Last Loop

--        If sString(index) /= ' ' Or Character'Pos(sString(index)) /= 9 Then
--          Null;
--        End If;

--        index := index + 1;

--      End Loop;

  fOpcode   : Ada.Text_IO.File_Type;
  fObject   : Ada.Text_IO.File_Type;
  fListing  : Ada.Text_IO.File_Type;

  sUnbounded  : Unbounded.Unbounded_String;
  sString     : String(1..3001);
  length      : Natural;
  size        : Integer := 1;
  Filename    : Unbounded.Unbounded_String;

--  Type tLines is Array (1..Parser.Size_Of_File("opcodes.txt")) of Parser.Line;
  aLines : tLines;

Begin

  Filename := Parser.Command_Line;

  size := Parser.Size_Of_File(Unbounded.To_String(Filename));

  Ada.Text_IO.open(fOpcode, Ada.Text_IO.IN_File, Unbounded.To_String(Filename));

  For index in 1..size Loop
    Ada.Text_IO.get_line(fOpcode, sString, length);
    aLines(index) := Parser.Explode (sString(1..length));
    Ada.Text_IO.put_line(Integer'Image(index) & Character'Val(9) & Unbounded.To_String(aLines(index).label));
  End Loop;

  Ada.Text_IO.close(fOpcode);

End assemble;
