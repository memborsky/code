With Ada.Text_IO;
With Ada.Strings.Unbounded;
With Ada.Integer_Text_IO;
With Parse;
With Opcodes;
With Output;
With Labels;
With Errors;

Procedure assemble is

  -- Constant Declaration for global handling.
  Max_File_Size   : Constant Integer := 1500;
  Max_Line_Length : Constant Integer := 132;

  -- Instantiate the Parser package.
  Package Parser is New Parse (Label_Seperator => ':', Comment_Label => ';');
  Package Macro is New Parser.Macro (Token => ''', Max_Number_of_Parameters => 12, Max_Macro_Length => Max_File_Size);

  -- Instiation of Table Package and variables.
  Type Opcodes_Range is (NOP, ADD, SUB, MUL, DIV, LDA, CLRA, LDX, LDY, STA,
      STX, STY, TXA, TYA, INA, INX, INY, SHFLA, SHFRA, JMP, JMPSUB, RET,
      JMPZ, JMPN, JMPC, READ, WRITE, PUSH, POP, STOP);

  Type Directives_Range is (dPROG, dEQU, dWORD, dBYTE, dBLOCK, dEND);

  Package Opcode is New Opcodes (Opcode_Range    => Opcodes_Range,
                                 Directive_Range => Directives_Range,
                                 Filename       => "data/opcodes.txt");

  Function Preprocess (Filename : String) Return String is

    Filename1       : String := "i" & Parser.Basename(Filename);
    Out_File_Handle : Ada.Text_IO.File_Type;
    In_File_Handle  : Aliased Ada.Text_IO.File_Type;
    Line_Data       : Parser.Line;
    Line_Number     : Integer := 1;

  Begin

    Ada.Text_IO.Create(Out_File_Handle, Ada.Text_IO.Out_File, Filename1);
    Ada.Text_IO.Open(In_File_Handle, Ada.Text_IO.In_File, Filename);

    While Not Ada.Text_IO.End_Of_File(In_File_Handle) Loop

      Declare

        Line_String : String  := Ada.Text_IO.Get_Line(In_File_Handle);
        Macro_Start : Integer := 0;
        Macro_End   : Integer := 0;

      Begin

        Line_Data := Parser.Parse(Line_String);

        If Parser.Get_Opcode(Line_Data) = ".MACRO" Then

          Macro.Append(Parser.Get_Label(Line_Data), Parser.Get_Operand(Line_Data), In_File_Handle);

        Else

          Declare

            Opcode  : String := Parser.Get_Opcode(Line_Data);
            Operand : String := Parser.Get_Operand(Line_Data);

          Begin

            If Opcode /= "" And Then Opcode(Opcode'First) /= '.' And Then Macro.Is_Macro(Opcode) Then
              Macro.Expand(Opcode, Operand, Out_File_Handle);
            Else
              Ada.Text_IO.Put_Line(Out_File_Handle, Line_String);
            End If;

          End;

        End If;

      End;

      Line_Number := Line_Number + 1;

    End Loop;

    Ada.Text_IO.Close(In_File_Handle);
    Ada.Text_IO.Close(Out_File_Handle);

    Return Filename1;

  End Preprocess;

  -- Handle the file that was sent in for assembling.
  Filename        : String := Preprocess(Parser.Command_Line);
  File_Handle     : Aliased Ada.Text_IO.File_Type;
  Out_File_Handle : Aliased Ada.Text_IO.File_Type;

  -- Line_Data is our line data that we get back from the parser.
  Type Line_Array is Array (1..Max_File_Size) of Parser.Line;
  Line_Data   : Line_Array;
  Line_Number : Integer := 0;

  -- Global varaibles
  --
  -- These variables define our registers.
  LOC : Integer := 0;

  Type Label_Record is Record
    label     : Ada.Strings.Unbounded.Unbounded_String;
    location  : Integer;
  End Record;

  -- Declaration of our global data array record for holding all the values
  -- of our current assembly program.
  Type Data_Record is Record
    label   : Label_Record; -- This contains the label and its location.
    opcode  : Integer; -- This contains the value of the opcode in base 10.
    operand : Integer; -- This contains the value of the operand in base 10.
    comment : Ada.Strings.Unbounded.Unbounded_String; -- Contains the comment in the line.
    error   : Boolean; -- This says if this line has an error on it or not.
    location : Integer; -- Holds the location of the line.
  End Record;

  Type Data_Array is Array(0..Max_File_Size) of Data_Record;
  Data : Data_Array;

  -- Extra variables needed to accomplish some tasks like quiting the program at
  -- different levels of scope
  EXIT_PROGRAM, INVALID_OPERAND, UNDEFINED_LABEL, MISSING_LABEL : Exception;

  --
  -- Pass1 - Pass one of the program we are assembling
  --
  -- @File_Handle - File_Type
  --
  Procedure Pass1 is

  Begin

    Ada.Text_IO.Open(File_Handle, Ada.Text_IO.IN_File, Filename);

    While Not Ada.Text_IO.End_Of_File(File_Handle) Loop

      Declare
        Line_String : String := Ada.Text_IO.Get_Line(File_Handle);
      Begin

        -- Increase our line number so we can have an accurate count of which line we
        -- are on for output of the data.
        Line_Number := Line_Number + 1;

        -- Parse the line we just go to our line record for manipulation on pass.
        Line_Data(Line_Number) := Parser.Parse(Line_String);
      End;

        -- Holds the location of the line.
        Data(Line_Number).location := LOC;

        Ada.Text_IO.Put_Line(Output.Display_Line (Line_Number, Parser.Get_Comment(Line_Data(Line_Number)), Parser.Get_Label(Line_Data(Line_Number)), Parser.Get_Opcode(Line_Data(Line_Number)), Parser.Get_Operand(Line_Data(Line_Number)), ""));

        -- Now we will move our data over from what the parser returned to us and
        -- convert it to what we want the values to be.

        -- Copy the comment as is, even if one doesn't exist on the line, the null
        -- Unbounded_String will be copied over.
        Data(Line_Number).comment := Ada.Strings.Unbounded.To_Unbounded_String(Parser.Get_Comment(Line_Data(Line_Number)));


        -- Check to make sure we have an opcode before we see if that opcode is
        -- actually a Directive. If so then skip the block to convert the opcode to
        -- a numerical value and go on.
        Declare
          sOpcode : String := Parser.Get_Opcode(Line_Data(Line_Number));
          error : Ada.Strings.Unbounded.Unbounded_String := Ada.Strings.Unbounded.Null_Unbounded_String;
        Begin
          If sOpcode /= "" Then
            If sOpcode(sOpcode'First) /= '.' Then
              Data(Line_Number).opcode := Opcode.To_Integer(Opcodes_Range'Value(sOpcode));
            Elsif sOpcode(sOpcode'First) = '.' Then
              Data(Line_Number).opcode := Opcode.To_Integer(Directives_Range'Value('d' & sOpcode(sOpcode'First + 1..sOpcode'Last)));
            End If;
          End If;

        -- Generate the Error message for an invalid operand.
        Exception
          When CONSTRAINT_ERROR =>
            Ada.Strings.Unbounded.Append(error, "Error : Invalid ");

            If sOpcode(1) /= '.' Then
              Ada.Strings.Unbounded.Append(error, "Opcode");
            Else
              Ada.Strings.Unbounded.Append(error, "Directive");
            End If;

            Ada.Strings.Unbounded.Append(error, " " & sOpcode);
            Errors.Append(Line_Number, Ada.Strings.Unbounded.To_String(error));
        End;


      -- If we have an operand, grab it back from the parser and check it for form.
      Declare
        sOpcode : String := Parser.Get_Opcode(Line_Data(Line_Number));
        sOperand : String := Parser.Get_Operand(Line_Data(Line_Number));
        sLabel : String := Parser.Get_Label(Line_Data(Line_Number));
        index : Integer := 1;
        temp1 : Integer := 0;
        temp2 : Integer := 0;
        mode  : Integer := 0;
      Begin
        If sOperand /= "" Then

          If sOpcode(1) = '.' Then

            If sOpcode(sOpcode'First + 1 .. sOpcode'Last) = "EQU" Then

              If Labels.Is_In(sOperand) Then

                Labels.Append(sLabel, Labels.Get_Value(sOperand));

              Elsif (Character'Pos(sOperand(index)) - 48) in 0..9 Then

                Loop

                  If (Character'Pos(sOperand(index)) - 48) in 0..9 Then
                    If temp1 = 0 Then
                      temp1 := (temp1 * 10) + (Character'Pos(sOperand(index)) - 48);
                    Else
                      temp2 := (temp2 * 10) + (Character'Pos(sOperand(index)) - 48);
                    End If;
                  End If;

                  If sOperand(index) = ' ' or Character'Pos(sOperand(index)) = 9 Then
                    If index + 1 /= sOperand'Last Then

                      index := index + 1;

                      If sOperand(index) = '+' Then
                        mode := 1;
                      Elsif sOperand(index) = '-' Then
                        mode := 2;
                      Elsif sOperand(index) = '*' Then
                        mode := 3;
                      End If;

                      If index + 1 /= sOperand'Last Then

                        index := index + 1;

                      End If;
                    Else
                      Raise INVALID_OPERAND;
                    End if;
                  End If;

                  Exit When index = sOperand'Last;

                  index := index + 1;

                End Loop;

                If sLabel /= "" Then

                  If mode = 1 Then
                    Labels.Append(sLabel, temp1 + temp2);
                  Elsif mode = 2 Then
                    Labels.Append(sLabel, temp1 - temp2);
                  Elsif mode = 3 Then
                    Labels.Append(sLabel, temp1 * temp2);
                  End If;

                Else

                  Raise MISSING_LABEL;

                End If;

              Else

                Raise UNDEFINED_LABEL;

              End If;

            Elsif sOpcode(sOpcode'First + 1 .. sOpcode'Last) = "BYTE" Then
              LOC := LOC + (Parser.Count_Commas(sOperand) + 1);
            Elsif sOpcode(sOpcode'First + 1 .. sOpcode'Last) = "WORD" Then
              LOC := LOC + (Parser.Count_Commas(sOperand) + 1) * 4;
            Elsif sOpcode(sOpcode'First + 1 .. sOpcode'Last) = "BLOCK" Then
              LOC := LOC + Integer'Value(sOperand);
            End If;

          Else

            If sLabel /= "" Then
              Labels.Append(sLabel, LOC);
            End If;

            If Data(Line_Number).opcode <= 100 And Then Data(Line_Number).opcode > 0 Then
              LOC := LOC + 4;
            Elsif Data(Line_Number).opcode > 100 Then
              LOC := LOC + 1;
            End If;

          End If;

        End If;

      Exception
        When INVALID_OPERAND =>
          Errors.Append(Line_Number, "Error : Invalid operand form.");

        When UNDEFINED_LABEL =>
          Errors.Append(Line_Number, "Error : Label " & sOperand & " is undefined.");

        When MISSING_LABEL =>
          Errors.Append(Line_Number, "Fatal Error : Can not use Equate directive without a defined label.");
          Raise EXIT_PROGRAM;

      End;

      Errors.Put_Errors(Line_Number);

    End Loop;

    Ada.Text_IO.Close(File_Handle);

  End Pass1;

  Procedure Pass2 is

    Filename1 : String := Parser.Basename(Filename(Filename'First + 1 .. Filename'Last), ".lis");
    Filename2 : String := Filename(Filename'First + 1 .. Filename'Last);

  Begin

    Ada.Text_IO.Create(Out_File_Handle, Ada.Text_IO.Out_File, Filename1);

    Output.New_Page(Out_File_Handle, Filename2);

    For Current_Line in 1 .. Line_Number Loop

      Output.New_Line(Out_File_Handle, Current_Line, Data(Current_Line).opcode, 0, Data(Current_Line).location,
          Parser.Get_Label(Line_Data(Current_Line)), Parser.Get_Opcode(Line_Data(Current_Line)),
          Parser.Get_Operand(Line_Data(Current_Line)), Parser.Get_Comment(Line_Data(Current_Line)));

      Errors.Put_Errors(Out_File_Handle, Current_Line);

    End Loop;

    Output.Label_Header(Out_File_Handle);

    For Current_Line in 1 .. Line_Number Loop

      If Parser.Get_Label(Line_Data(Current_Line)) /= "" Then
        Output.New_Label(Out_File_Handle, Parser.Get_Label(Line_Data(Current_Line)), Labels.Get_Value(Parser.Get_Label(Line_Data(Current_Line))));
      End If;

    End Loop;

    Ada.Text_IO.Close(Out_File_Handle);

  End Pass2;

Begin

  -- Run the first pass.
  Pass1;

  -- Run the second pass.
  Pass2;

Exception
  When EXIT_PROGRAM =>
    Errors.Put_Errors(Line_Number);

End assemble;
