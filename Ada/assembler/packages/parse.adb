With Ada.Strings.Unbounded;
With Ada.Integer_Text_IO;
With Ada.Strings.Fixed;
Use Ada.Strings.Unbounded;

-----
-- Parse various needs to the assembler.
--
-- This package will parse multiple items for our assember. This includes the command line and the actually
-- assembly file that we are assembling. We even parse the operand to get the type of operand we have.
--
-- @access Public
-- @author Matt Emborsky <mlemborsky01@indianatech.net>
-----
Package Body parse is

  -- Basic setup for null unbounded string.
  nus : Ada.Strings.Unbounded.Unbounded_String := Ada.Strings.Unbounded.Null_Unbounded_String;

  -----
  -- Skip White Spaces
  --
  -- This procedure goes through the given string and finds the next non-space or non-tab character to start the next field.
  --
  -- @access Public
  -----
  Procedure Skip_White_Space(sLine : IN String; index : IN OUT Integer) is

  Begin

    -- Check for space or tab.
    While sLine(index) = ' ' or Character'Pos(sLine(index)) = 9 Loop
      -- Must have trailing spaces for some strange reason and need to break the loop to not raise an error.
      Exit When sLine'Last = index;

      index := index + 1;
    End Loop;

  End Skip_White_Space;

  -----
  -- Count number of commas
  --
  -- Count the number of commas in the string.
  --
  -- @access Public
  -----
  Function Count_Commas (temp : String) Return Integer is

    Result : Integer := 0;
    index  : Integer := 1;

  Begin

    Loop

      Skip_White_Space(temp, index);

      If temp(index) = ',' Then
        Result := Result + 1;
      End If;

      index := index + 1;

      Exit When index = temp'Last;

    End Loop;

    Return Result;

  End Count_Commas;

  -----
  -- Parse the line
  --
  -- This will parse our line, given that we have gotten the line from the file and are sending it into this function.
  -- We return a pointer reference to the location of our Line data type.
  --
  -- @access Public
  -----
  Function Parse (sLine : IN String) Return Line is

    Exploded_Line : Line;
    unboundTemp   : Ada.Strings.Unbounded.Unbounded_String := nus;
    index         : Integer := 1;

    Label_Flag    : Boolean := False;


    -- This function checks to see if : is in the line. If it is, then we have
    -- a label, else we assume that a label does not exist in the line.
    Function Check_For_Label (Line : IN String) Return Boolean is

    Begin

      For count in 1..Line'Last Loop
        If Line(count) = Label_Seperator Then
          Return True;
        End If;
      End Loop;

      Return False;

    End Check_For_Label;

    -- Type used to be able to run a case on which piece of data we are searching to get back from the
    -- file in terms of parsing.
    Type Fields is (LABEL, OPCODE, OPERAND, COMMENT);

    Procedure Get (Field : IN Fields; Line : IN String; Current_Index : IN OUT Integer; Return_String : OUT Ada.Strings.Unbounded.Unbounded_String) is

    Begin

      Skip_White_Space(Line, Current_Index);

      -- Switch on Fields (LABEL, OPCODE, OPERAND, COMMENT)
      Case Field is

        -- If we are getting the comment, then we need to just return from the current index location to the end of the line.
        When COMMENT  =>
          Return_String := Ada.Strings.Unbounded.To_Unbounded_String(Line(Current_Index .. Line'Last));
          Current_Index := Line'Last;


        -- If we are getting the label then we need to loop until we find either a space/tab or the Label_Seperator.
        When LABEL    =>
          If Line(Current_Index) /= Label_Seperator Then
            Loop
              Exit When Line(Current_Index) = ' ' or Character'Pos(Line(Current_Index)) = 9 or Line(Current_Index) = Label_Seperator;
              Ada.Strings.Unbounded.Append(Return_String, Line(Current_Index));

              -- Basic to make sure we don't over flow the string and create a buffer-overflow type error on the string.
              Exit When Line'Last = Current_Index;
              Current_Index := Current_Index + 1;
            End Loop;
          End If;
          Current_Index := Current_Index + 1;


        -- If we are getting the opcode, then we need to loop until we find a space/tab.
        When OPCODE   =>
          Loop
            Exit When Line(Current_Index) = ' ' or Character'Pos(Line(Current_Index)) = 9;
            Ada.Strings.Unbounded.Append(Return_String, Line(Current_Index));

            -- If we exit from this line, then we are at the end of the string and must only have a 1 byte opcode.
            Exit When Line'Last = Current_Index;
            Current_Index := Current_Index + 1;
          End Loop;

        -- If we are getting the operand, then we need to loop until we find a either a Comment_Label or we reach
        -- the end of our string due to an invalid line of data.
        When OPERAND  =>
          Loop
            Exit When Line(Current_Index) = Comment_Label;
            Ada.Strings.Unbounded.Append(Return_String, Line(Current_Index));

            -- Exit if we reached the end of the line.
            Exit When Line'Last = Current_Index;
            Current_Index := Current_Index + 1;
          End Loop;

      End Case;

      Skip_White_Space(sLine, Current_Index);

    End Get;

  Begin

    -- Remove the white space before our first chracter.
    Skip_White_Space(sLine, index);

    -- If the first character is a ; then we have a full line comment and we can just
    -- skip over it. If we don't see a ; then we need to continue on our checklist.
    If sLine(index) /= Comment_Label Then

      -- Check to see if the line we are currently given has a label.
      If Check_For_Label(sLine) Then

          -- Then store the it into our return record.
          Get(Fields'Value("LABEL"), sLine, index, Exploded_Line.Label);

      End If;

      -- If we are not at the end of the string ...
      If sLine'Last /= index Then

        -- Then we can continue and grab the opcode from the string and store it into the return record.
        Get(Fields'Value("OPCODE"), sLine, index, Exploded_Line.Opcode);

      End If;

      -- If we are not at the end of the string ...
      If sLine'Last /= index Then

        -- Then we can continue and grab the operand from the string and store it into the return record.
        Get(Fields'Value("OPERAND"), sLine, index, Exploded_Line.Operand);

      End If;

      -- If we are not at the end of our string yet ....
      If sLine'Last /= index Then

        -- Then we must have a comment and need to put it into our return record.
        Get(Fields'Value("COMMENT"), sLine, index, Exploded_Line.Comment);

      End If;

    Else

      -- Grab the comment and chuck it into our return record.
      Get(Fields'Value("COMMENT"), sLine, index, Exploded_Line.Comment);

    End If;

    -- Return our newly formed line in record form. This will be easier to parse later.
    Return Exploded_Line;

  End Parse;

  -----
  -- Parse the command line
  --
  -- This function will parse the command line and return the Filename of our file we are wanting to assemble.
  --
  -- @access Public
  -----
  Function Command_Line Return String is

    Filename : Ada.Strings.Unbounded.Unbounded_String;

  Begin

    If Ada.Command_Line.Argument_Count >= 1 Then
      Filename := Ada.Strings.Unbounded.To_Unbounded_String(Ada.Command_Line.Argument(1));
    Else
      Ada.Text_IO.Put_Line("Command Usage: ./assemble <program to assemble>");
      Raise No_File_Argument;
    End If;

    Return Ada.Strings.Unbounded.To_String(Filename);

  End Command_Line;


  -----
  -- Return the opcode as a string.
  --
  -- Pull the opcode out of the line reference and return it as a string.
  --
  -- @access Public
  -----
  Function Get_Opcode (Data : Line) Return String is
  Begin
    Return Ada.Strings.Unbounded.To_String(Data.opcode);
  End Get_Opcode;


  -----
  -- Return the operand as a string.
  --
  -- Pull the operand out of the line refrence and return it as a string.
  --
  -- @access Public.
  -----
  Function Get_Operand (Data : Line) Return String is
  Begin
    Return Ada.Strings.Unbounded.To_String(Data.operand);
  End Get_Operand;


  -----
  -- Return the label as a string.
  --
  -- Pull the label out of the line reference and return it as a string.
  --
  -- @access Public
  -----
  Function Get_Label (Data : Line) Return String is
  Begin
    If Data.Label = Ada.Strings.Unbounded.Null_Unbounded_String Then
      Return "";
    Else
      Return Ada.Strings.Unbounded.To_String(Data.label);
    End If;
  End Get_Label;

  -----
  -- Is_In
  --
  -- Check if needle is in haystack.
  --
  -- @access Public
  -----
  Function Is_In (haystack : String; needle : Character) Return Boolean is

    IsIn  : Boolean := False;
    Index : Integer := haystack'First;

  Begin

    While Index <= haystack'Last Loop

      If haystack(Index) = needle Then

        IsIn := True;

      End If;

      Index := Index + 1;

    End Loop;

    Return IsIn;

  End Is_In;

  -----
  -- Basename
  --
  -- Find the basename of the file we are given.
  --
  -- @access Public
  -----
  Function Basename (Filename : String; extension : String := "") Return String Is

    Index : Integer := Filename'First;
    Backtrace : Integer := Filename'Last;

  Begin

    While Index <= Filename'Last Loop

      -- Exit when we have no more / in the path name.
      Exit When Not Is_In(Filename(Index..Filename'Last), '/');

      -- Move down the string.
      Index := Index + 1;

    End Loop;

    -- If the extension is empty then
    If extension = "" Then

      -- We don't care to replace the extension and just need to return the basename.
      Return Filename(Index..Backtrace);

    Else

      -- Find the starting point of the previous extension.
      While Filename(Backtrace) /= '.' Loop
        Backtrace := Backtrace - 1;
      End Loop;

      -- If we are at the beginning of the return string, and our calculations were correct above here, we just need to
      If Index = Backtrace And Then Filename(Index) /= '.' Then

        -- Append the extension
        Return Filename(Index..Filename'Last) & extension;

      Else

        -- Replace the extension
        Return Filename(Index..Backtrace - 1) & extension;

      End If;

    End If;

  End Basename;


  -----
  -- Return the comment as a string.
  --
  -- Pull the comment out of the line reference and return it as a string.
  --
  -- @access Public
  -----
  Function Get_Comment (Data : Line) Return String is
  Begin
    Return Ada.Strings.Unbounded.To_String(Data.comment);
  End Get_Comment;


  -----
  -- Macro Pre-Processor package
  --
  -- Handles all of our Macro Pre-Processor functionality.
  --
  -- @access Public
  -----
  Package Body Macro is
  
    -- Holds the pointer to the head of the Macro Table.
    Head : Pointer_Macro_Table := Null;

    -- Holds current number value for the next system label.
    System_Label_Number : Integer := 0;

    -----
    -- Is Macro
    --
    -- Returns true if the macro name is a defined macro table.
    --
    -- @access Public
    -----
    Function Is_Macro (Macro_Name : String) Return Boolean is

      Scan : Pointer_Macro_Table := Head;

    Begin

      Loop

        If Ada.Strings.Unbounded.To_String(Scan.Name) = Macro_Name Then

          Return True;

        End If;

        Exit When Scan.Next = null;

        Scan := Scan.Next;

      End Loop;

      Return False;

    End Is_Macro;

    -----
    -- Get Macro Table
    --
    -- This will search the macro table list and pull the macro table out for our macro name.
    --
    -- @access Private
    -----
    Function Get_Macro_Table (Macro_Name : String) Return Macro_Table is

      Scan : Pointer_Macro_Table := Head;
      Result : Macro_Table;

    Begin

      Result.Name := nus;
      Result.Lines(0) := nus;
      Result.Next := null;

      Loop

        If Scan.Name = Macro_Name Then

          Result.Name := Scan.Name;
          Result.Formal := Scan.Formal;
          Result.Lines := Scan.Lines;
          Result.Next := null;

        End If;

        Exit When Scan.Next = null;

        Scan := Scan.Next;

      End Loop;

      Return Result;

    End Get_Macro_Table;


    -----
    -- Tokenize Macro
    --
    -- This procedure will take the given macro name and tokenize it for future parameter replacement.
    --
    -- @access Private
    -----
    Function Tokenize (Line : String; Parameter_List : Parameter_Array) Return String is

      Result            : Ada.Strings.Unbounded.Unbounded_String := Ada.Strings.Unbounded.To_Unbounded_String(Line);
      Current_Parameter : Integer := Parameter_List'First;

    Begin

      Loop

        Exit When Ada.Strings.Unbounded.To_String(Parameter_List(Current_Parameter)) = "";

        Declare

          Param : String := Ada.Strings.Unbounded.To_String(Parameter_List(Current_Parameter));

        Begin

          If Param(Param'First) = '%' And Then Ada.Strings.Unbounded.Index(Result, Param(Param'First + 1 .. Param'Last)) > 0 Then

            Ada.Strings.Unbounded.Insert(Result, Ada.Strings.Unbounded.Index(Result, Param(Param'First + 1 .. Param'Last)), Token & "%");
                      
          Elsif Ada.Strings.Unbounded.Index(Result, Param) > 0 Then
        
            Ada.Strings.Unbounded.Insert(Result, Ada.Strings.Unbounded.Index(Result, Param), Token & "");
          
          End If;
          
        End;
        
        Exit When Current_Parameter = Parameter_List'Last;
        Current_Parameter := Current_Parameter + 1;

      End Loop;

      Return Ada.Strings.Unbounded.To_String(Result);

    End Tokenize;


    -----
    -- Biuld Parameter Array
    --
    -- This will build the parameter list array for replacement on the line.
    --
    -- @access Private
    -----
    Function Build_Parameter_Array (Parameter_List : Ada.Strings.Unbounded.Unbounded_String) Return Parameter_Array is

      Result  : Parameter_Array;
      Temp    : Ada.Strings.Unbounded.unbounded_String := nus;
      Current : Integer := Result'First;
      Parameters_List : String := Ada.Strings.Unbounded.To_String(Parameter_List);
      Position : Integer := Parameters_List'First;

    Begin

      Loop

        Skip_White_Space(Ada.Strings.Unbounded.To_String(Parameter_List), Position);

        If Parameters_List(Position) = ',' or Position = Parameters_List'Last Then

          If Position = Parameters_List'Last And Then Parameters_List(Position) /= ',' Then
            Ada.Strings.Unbounded.Append(Temp, Parameters_List(Position));
          Elsif Temp = nus Then
            Ada.Strings.Unbounded.Append(Temp, "");
          Else
            Ada.Strings.Unbounded.Append(Temp, "");
          End If;

          Result(Current) := Temp;
          Temp := nus;
          Current := Current + 1;

        Else

          Ada.Strings.Unbounded.Append(Temp, Parameters_List(Position));

        End If;

        Exit When Position = Parameters_List'Last;
        Position := Position + 1;

      End Loop;

      For Rest in Current .. Result'Last Loop
        Result(Rest) := nus;
      End Loop;

      Return Result;

    End Build_Parameter_Array;


    -----
    -- Append
    --
    -- Append a new macro to the macro table.
    --
    -- @access Private
    -----
    Procedure Append (Macro_Name : String; Formal_Parameter_List : String; File_Handle : Ada.Text_IO.File_Type) is

      Type Line_Array is Array (0 .. Max_Macro_Length) of Line;
      Line_Data   : Line_Array;
      Temp_Table  : Macro_Table;
      Line_Number : Integer := Temp_Table.Lines'First;
      New_Macro   : Pointer_Macro_Table := Null;

    Begin

      Temp_Table.Formal := Build_Parameter_Array(Ada.Strings.Unbounded.To_Unbounded_String(Formal_Parameter_List));
      Temp_Table.Name   := Ada.Strings.Unbounded.To_Unbounded_String(Macro_Name);

      Loop

        Line_Data(Line_Number) := Parse(Ada.Text_IO.Get_Line(File_Handle));

        Exit When Ada.Strings.Unbounded.To_String(Line_Data(Line_Number).Opcode) = ".ENDM";

        If Line_Data(Line_Number).Opcode = ".MACRO" Then

          Append(Ada.Strings.Unbounded.To_String(Line_Data(Line_Number).Label), Ada.Strings.Unbounded.To_String(Line_Data(Line_Number).Operand), File_Handle);

        End If;

        If Line_Data(Line_Number).Label /= nus Then
        
          Temp_Table.Lines(Line_Number) := Ada.Strings.Unbounded.To_Unbounded_String(Tokenize(
            Ada.Strings.Unbounded.To_String(Line_Data(Line_Number).Label) & ": " & 
            Ada.Strings.Unbounded.To_String(Line_Data(Line_Number).Opcode) & " " & 
            Ada.Strings.Unbounded.To_String(Line_Data(Line_Number).Operand), 
            Temp_Table.Formal));

        Else

          Temp_Table.Lines(Line_Number) := Ada.Strings.Unbounded.To_Unbounded_String(Tokenize(
            Ada.Strings.Unbounded.To_String(Line_Data(Line_Number).Label) & " " & 
            Ada.Strings.Unbounded.To_String(Line_Data(Line_Number).Opcode) & " " & 
            Ada.Strings.Unbounded.To_String(Line_Data(Line_Number).Operand), 
            Temp_Table.Formal));

        End If;

        Line_Number := Line_Number + 1;

      End Loop;

      -- Create new table and append to the macro table.
      New_Macro := New Macro_Table'(Name => Temp_Table.Name, Formal => Temp_Table.Formal, Lines => Temp_Table.Lines, Next => Head);

      Head := New_Macro;
      

    End Append;


    -----
    -- Generate System Labels
    --
    -- Generate System Labels for each Formal that contains a % in it.
    --
    -- @access Private
    -----
    Procedure Generate_System_Labels (Formal : Parameter_Array; Actual : IN OUT Parameter_Array) is

      System_Label_String : String := "SysLabel";
      Current_Parameter : Integer := Formal'First;

    Begin

      Loop

        Exit When Formal(Current_Parameter) = nus;

        If Is_In(Ada.Strings.Unbounded.To_String(Formal(Current_Parameter)), '%') Then

          Declare
            Sys_Lab_Num_String : String := Integer'Image(System_Label_Number);
          Begin
            Actual(Current_Parameter) := Ada.Strings.Unbounded.To_Unbounded_String(System_Label_String & Sys_Lab_Num_String(Sys_Lab_Num_String'First + 1 .. Sys_Lab_Num_String'Last));
          End;

          System_Label_Number := System_Label_Number + 1;

        End If;

        Exit When Current_Parameter = Formal'Last;
        Current_Parameter := Current_Parameter + 1;

      End Loop;

    End Generate_System_Labels;


    -----
    -- Formal to Actual
    --
    -- This converts the tokenized formals to the actuals by text-to-text.
    --
    -- @access Private
    -----
    Function Formal_To_Actual (Line : Ada.Strings.Unbounded.Unbounded_String; Formal : Parameter_Array; Actual : Parameter_Array) Return String is

      Current_Parameter : Integer := Formal'First;
      Result : Ada.Strings.Unbounded.Unbounded_String := nus;

    Begin

      If Is_In(Ada.Strings.Unbounded.To_String(Line), Token) Then

        Loop

          Exit When Formal(Current_Parameter) = nus;

          Declare

            Parameter_Place : Natural;
            Parameter_Size  : Natural := Ada.Strings.Unbounded.Length(Formal(Current_Parameter));

          Begin

            Loop

              If Result = nus Then
                Parameter_Place := Ada.Strings.Unbounded.Index(Line, Token & Ada.Strings.Unbounded.To_String(Formal(Current_Parameter)));
              Else
                Parameter_Place := Ada.Strings.Unbounded.Index(Result, Token & Ada.Strings.Unbounded.To_String(Formal(Current_Parameter)));
              End If;

              Exit When Parameter_Place <= 0;

              If Result = nus Then
                Result := Ada.Strings.Unbounded.Delete(Line, Parameter_Place, Parameter_Place + Parameter_Size);
              Else
                Ada.Strings.Unbounded.Delete(Result, Parameter_Place, Parameter_Place + Parameter_Size);
              End If;

              If Actual(Current_Parameter) /= nus Then

                Ada.Strings.Unbounded.Insert(Result, Parameter_Place, Ada.Strings.Unbounded.To_String(Actual(Current_Parameter)));

              End If;

            End Loop;

          End;

          Exit When Current_Parameter = Formal'Last;
          Current_Parameter := Current_Parameter + 1;

        End Loop;

        Return Ada.Strings.Unbounded.To_String(Result);

      Else

        Return Ada.Strings.Unbounded.To_String(Line);

      End If;

    End Formal_To_Actual;


    -----
    -- Macro Expander
    --
    -- Expand our Macros
    --
    -- @access Public
    -----
    Procedure Expand (Macro_Name : String; Actual_Parameter : String; File_Handle : Ada.Text_IO.File_Type) is

      Actual    : Parameter_Array := Build_Parameter_Array(Ada.Strings.Unbounded.To_Unbounded_String(Actual_Parameter));
      If_Block  : Boolean := False;
      Out_If    : Boolean := False;
      Skip_Check : Boolean := False;

    Begin

      If Is_Macro(Macro_Name) Then

        Declare

          Macro       : Macro_Table := Get_Macro_Table(Macro_Name);
          Line_Number : Integer := Macro.Lines'First;

        Begin

          Generate_System_Labels(Macro.Formal, Actual);

          Loop

            Exit When Macro.Lines(Line_Number) = nus;

            Declare

              Line_String : String := Formal_To_Actual(Macro.Lines(Line_Number), Macro.Formal, Actual);

            Begin

              If Ada.Strings.Fixed.Index(Line_String, ".IF") > 0 or If_Block Then

                If_Block := True;

                If Ada.Strings.Fixed.Index(Line_String, ".ENDIF") > 0 Then

                  If_Block := False;
                  Out_If := False;
                  Skip_Check := False;

                Else

                  If Not Out_If And Then Not Skip_Check Then

                    Declare

                      Start_Binary  : Natural;
                      End_Binary    : Natural;
                      Type Operands is Array (0..1) of Ada.Strings.Unbounded.Unbounded_String;

                      Function Get_Operands (Line : String; OStart : Natural; OEnd : Natural) Return Operands is

                        Result : Operands;
                        Current : Integer := OStart - 1;
                        String : Ada.Strings.Unbounded.Unbounded_String := nus;
                        Position : Natural := 0;

                      Begin

                        Loop

                          Ada.Strings.Unbounded.Head(String, Position, Line(Current));
                          Exit When Line(Current - 1) = ' ' or Character'Pos(Line(Current - 1)) = 9;
                          Current := Current - 1;
                          Position := Position + 1;

                        End Loop;
                        Result(0) := String;

                        String := nus;
                        Current := OEnd + 1;

                        If Current < Line'Last Then

                          Loop

                            Ada.Strings.Unbounded.Append(String, Line(Current));
                            Exit When Current >= Line'Last;
                            Current := Current + 1;

                          End Loop;

                        Else

                          Ada.Strings.Unbounded.Append(String, "");

                        End If;

                        Result(1) := String;

                        Return Result;

                      End Get_Operands;

                      Operand : Operands;

                    Begin

                      If Ada.Strings.Fixed.Index(Line_String, ".EQ.") > 0 Then

                        Start_Binary := Ada.Strings.Fixed.Index(Line_String, ".EQ.");
                        End_Binary := Start_Binary + 4;

                        Operand := Get_Operands(Line_String, Start_Binary, End_Binary);

                        If Operand(0) = Operand(1) Then

                          Out_If := True;
                          Skip_Check := True;

                        End If;

                      Elsif Ada.Strings.Fixed.Index(Line_String, ".LS.") > 0 Then

                        Start_Binary := Ada.Strings.Fixed.Index(Line_String, ".LS.");
                        End_Binary := Start_Binary + 4;

                        Operand := Get_Operands(Line_String, Start_Binary, End_Binary);

                        If Operand(0) < Operand(1) Then

                          Out_If := True;
                          Skip_Check := True;

                        End If;

                      Elsif Ada.Strings.Fixed.Index(Line_String, ".GT.") > 0 Then

                        Start_Binary := Ada.Strings.Fixed.Index(Line_String, ".GT.");
                        End_Binary := Start_Binary + 4;

                        Operand := Get_Operands(Line_String, Start_Binary, End_Binary);

                        If Operand(0) > Operand(1) Then

                          Out_If := True;
                          Skip_Check := True;

                        End If;

                      Elsif Ada.Strings.Fixed.Index(Line_String, ".EMPTY.") > 0 Then

                        Start_Binary := Ada.Strings.Fixed.Index(Line_String, ".EMPTY.");
                        End_Binary := Start_Binary + 7;

                        Operand := Get_Operands(Line_String, Start_Binary, End_Binary);

                        If Operand(1) = nus Then

                          Out_If := True;
                          Skip_Check := True;

                        End If;

                      End If;

                    End;

                  Else

                    Ada.Text_IO.Put_Line(File_Handle, Line_String);

                  End If;

                End If; -- .ENDIF

              Else -- .IF

                Ada.Text_IO.Put_Line(File_Handle, Line_String);

              End If; -- .IF

            End;

            Exit When Line_Number = Macro.Lines'Last;
            Line_Number := Line_Number + 1;

          End Loop;
          
        End;

      Else

        Raise INVALID_MACRO_NAME;

      End If;

    End Expand;

  End Macro;

End parse;
