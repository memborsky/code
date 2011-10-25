With Ada.Strings.Unbounded;
With Ada.Text_IO;
With Ada.Command_Line;

-- This package will allow us to parse many features of the assembler program.
-- This includes parsing the file for the total number of lines to exploding
-- each line so that we can do quicker testing on the opcodes and operands.

-- @ = Public
-- # = Private

-- Variables:
--  @Line is #Record
--  @Element_Type

-- Procedures:
--  @Parse -> ()
--  @Size_Of_File -> (Handle <=> Ada.Text_IO.File_Type; Result <= Integer)

-- Functions:
--  @Size_Of_File -> (file => String) -> [Integer]
--  #explode -> (sString => String) -> [Line]

Package Body parse is

  -- The only thing this function does is calculate the number of lines in
  -- the given file so that we can define our array size. It is placed here
  -- because it relates to the parsing of the file.
  -- <=> Handle : Ada.Text_IO.File_Type
  -- <=  Result : Integer
  Function Size_Of_File (File : IN String) Return Integer is

    Result : Integer := 0;
    Handle : Ada.Text_IO.File_Type;

  Begin

    Ada.Text_IO.Open(Handle, Ada.Text_IO.IN_File, File);

    While Not Ada.Text_IO.End_Of_File(Handle) Loop
      Ada.Text_IO.Skip_Line(Handle);
      Result := Result + 1;
    End Loop;

    Ada.Text_IO.Close(Handle);

    Return Result;

  End Size_Of_File;


  -- This function takes a string and breaks it up into each of its fields.
  Function explode(sLine : IN String) Return Line is

    Exploded_Line : Line;
    unboundTemp   : Ada.Strings.Unbounded.Unbounded_String := Ada.Strings.Unbounded.Null_Unbounded_String;
    index    : Integer := 1;

    -- This procedure goes through the given string and finds the next non-space
    -- or non-tab character to start the next field.
    Procedure Skip_White_Space(sLine : IN String; index : IN OUT Integer) is

    Begin

      -- Check for space or tab.
      While sLine(index) = ' ' or Character'Pos(sLine(index)) = 9 Loop
        index := index + 1;
      End Loop;

    End Skip_White_Space;


    -- This function checks to see if : is in the line. If it is, then we have
    -- a label, else we assume that a label does not exist in the line.
    Function Check_For_Label (temp : IN String) Return Boolean is

      nReturn : Boolean := False;

    Begin

      For count in 1..temp'Last Loop
        If temp(count) = ':' Then
          nReturn := True;
        End If;
      End Loop;

      Return nReturn;

    End Check_For_Label;

  Begin

    -- Remove the white space before our first chracter.
    Skip_White_Space(sLine, index);

    -- If the first character is a ; then we have a full line comment and we can just skip over it.
    -- If we don't see a ; then we need to continue on our checklist.
    If sLine(index) /= ';' Then

      -- If we are here, then we don't have a full line comment and we need to check to see if a
      -- label exists in the line to know if we need to pick it out of the string or not.
      If Check_For_Label(sLine) Then

        -- Loop until we find our pre-defined label seperator.
        Loop
          Exit When sLine(index) = Label_Seperator;
          Ada.Strings.Unbounded.Append(unboundTemp, sLine(index));
          index := index + 1;
        End Loop;

        Exploded_Line.label := unboundTemp;
        unboundTemp := Ada.Strings.Unbounded.Null_Unbounded_String;
        index := index + 1;

      End If;

      -- Skip the white space between words.
      Skip_White_Space(sLine, index);

      -- Now we need to grab the opcode. If we see a ; then we need to generate an error for
      -- the line due to an unassigned label.
      Loop
        Exit When sLine(index) = ' ' or Character'Pos(sLine(index)) = 9;
        Ada.Strings.Unbounded.Append(Exploded_Line.opcode, sLine(index));
        index := index + 1;
      End Loop;

      -- SKip the white space between words.
      Skip_White_Space(sLine, index);

      -- Now we need to grab the operand. If we see a ; then the opcode should be a one bit
      -- opcode that doesn't need an operand, however we don't generate errors on this pass.
      -- We will need to check the opcode on the next pass.
      Loop
        Ada.Strings.Unbounded.Append(Exploded_Line.operand, sLine(index));
        Exit When sLine(index) = ';' or index = sLine'Last;
        index := index + 1;
      End Loop;

      -- If we are not at the end of the line, then we must have a comment, else we have an
      -- error. However; we shouldn't generate the error until the second pass.
      If sLine(index) = ';' Then
        Exploded_Line.comment := Ada.Strings.Unbounded.To_Unbounded_String(sLine(index..sLine'Last));
        index := index + 1;
      End If;

    Else

      Exploded_Line.comment := Ada.Strings.Unbounded.To_Unbounded_String(sLine(index..sLine'Last));

    End If;

    Return Exploded_line;

  End explode;

  -- This procedure will take care of going through our input file and breaking the line out
  -- so we can do manipulation of the data. This will be used on pass 1 and pass 2.
  Procedure Parse (Handle : Access Ada.Text_IO.File_Type; Exploded_Lines : IN OUT Lines) is

    index : Integer := 1;

  Begin

    While Not Ada.Text_IO.End_Of_File(Handle.all) Loop
      Declare
        sLine : String := Ada.Text_IO.Get_Line(Handle.all);
      Begin
        Exploded_Lines(index) := Explode(sLine);
        index := index + 1;
      End;
    End Loop;

  End parse;


  -- This function parses the command line and returns an unbounded string of the file name
  -- that we are wanting to assemble.
  Function Command_Line Return Ada.Strings.Unbounded.Unbounded_String is

    Filename : Ada.Strings.Unbounded.Unbounded_String;

  Begin

    If Ada.Command_Line.Argument_Count >= 1 Then
      Filename := Ada.Strings.Unbounded.To_Unbounded_String(Ada.Command_Line.Argument(1));
    Else
      Ada.Text_IO.Put_Line("Command Usage: ./assemble <program to assemble>");
      Raise No_Argument;
    End If;

    Return Filename;

  End Command_Line;

End parse;
