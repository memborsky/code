With Ada.Strings.Unbounded;
With Ada.Text_IO;

Generic

  Label_Seperator : Character;

Package parse is

  Type Line is Record
    label   : Ada.Strings.Unbounded.Unbounded_String;
    opcode  : Ada.Strings.Unbounded.Unbounded_String;
    operand : Ada.Strings.Unbounded.Unbounded_String;
    comment : Ada.Strings.Unbounded.Unbounded_String;
    error   : Integer;
  End Record;

  Type Lines is Array (Positive Range <>) of Line;

  No_Argument : Exception;

  Function Size_Of_File (File : IN String) Return Integer;

  Function Command_Line Return Ada.Strings.Unbounded.Unbounded_String;

  Procedure Parse (Handle : Access Ada.Text_IO.File_Type; Exploded_Lines : IN OUT Lines);

End parse;
