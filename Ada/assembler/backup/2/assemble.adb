With Ada.Text_IO;
With Ada.Strings.Unbounded;
With Ada.Integer_Text_IO;
With Parse;

Procedure assemble is

  Package Parser is New Parse (Label_Seperator => ':');

  Filename : Ada.Strings.Unbounded.Unbounded_String := Parser.Command_Line;
  File_Handle : Aliased Ada.Text_IO.File_Type;

  nLines : Integer := Parser.Size_Of_File(Ada.Strings.Unbounded.To_String(Filename));
  aLines : Parser.Lines(1..nLines);

  output : Ada.Strings.Unbounded.Unbounded_String;

Begin

  Ada.Text_IO.Open(File_Handle, Ada.Text_IO.IN_File, Ada.Strings.Unbounded.To_String(Filename));

  For index in 1..nLines Loop
    Parser.Parse(File_Handle'Access, aLines);
    Ada.Strings.Unbounded.Append(output, Integer'Image(index));

    Declare
      label : Boolean := False;
      opcode : Boolean := False;
      operand : Boolean := False;
    Begin
      If Ada.Strings.Unbounded.To_String(aLines(index).label) /= "" Then
        Ada.Strings.Unbounded.Append(output, "  " & Ada.Strings.Unbounded.To_String(aLines(index).label) & ":");
        label := True;
      End If;

      If Ada.Strings.Unbounded.To_String(aLines(index).opcode) /= "" Then
        If label Then
          Ada.Strings.Unbounded.Append(output, " ");
        Else
          Ada.Strings.Unbounded.Append(output, Character'Val(9) & "  ");
        End If;
        Ada.Strings.Unbounded.Append(output, Ada.Strings.Unbounded.To_String(aLines(index).opcode));
        opcode := True;
      End If;

      If Ada.Strings.Unbounded.To_String(aLines(index).operand) /= "" Then
        If label And opcode Then
          Ada.Strings.Unbounded.Append(output, "  ");
        Elsif Not label And opcode Then
          Ada.Strings.Unbounded.Append(output, "  ");
        Else
          Ada.Strings.Unbounded.Append(output, Character'Val(9) & "  ");
        End If;
        Ada.Strings.Unbounded.Append(output, Ada.Strings.Unbounded.To_String(aLines(index).operand));
      End If;

      If Ada.Strings.Unbounded.To_String(aLines(index).comment) /= "" Then
        If label And opcode And operand Then
          Ada.Strings.Unbounded.Append(output, "  ");
        Elsif label And opcode And Not operand Then
          Ada.Strings.Unbounded.Append(output, "  ");
        Elsif label And Not opcode And Not operand Then
          Ada.Strings.Unbounded.Append(output, "  ");
       Else
          Ada.Strings.Unbounded.Append(output, Character'Val(9) & "  ");
        End If;
        Ada.Strings.Unbounded.Append(output, Ada.Strings.Unbounded.To_String(aLines(index).comment));
      End If;
    End;

    Ada.Text_IO.Put_Line(Ada.Strings.Unbounded.To_String(output));
    output := Ada.Strings.Unbounded.To_Unbounded_String("");
  End Loop;

  Ada.Text_IO.Close(File_Handle);

End assemble;
