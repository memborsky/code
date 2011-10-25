With Ada.Text_IO;
With Ada.Integer_Text_IO;
With Ada.Strings.Unbounded;

-----
-- Handle our output.
--
-- This package will take care of all our output control and allow us to centralize the output for
-- generic package in the main assembler program.
--
-- @package output
-- @access  public
-- @author  Matt Emborsky <mlemborsky01@indianatech.net>
-----
Package Body output is

  -- Holds the current page number for number purposes.
  Page_Number : Integer := 0;

  -----
  -- Display new line.
  --
  -- Return our new line that we would create in the listing file.
  --
  -- @access public
  -----
  Function Display_Line (Line_Number : Integer;
                    Comment : String;
                    Label   : String;
                    Original_Opcode  : String;
                    Original_Operand : String;
                    Error   : String)
  Return String is

    Label_Flag   : Boolean := False;
    Opcode_Flag  : Boolean := False;
    Operand_Flag : Boolean := False;

    output : Ada.Strings.Unbounded.Unbounded_String := Ada.Strings.Unbounded.Null_Unbounded_String;

  Begin

    -- Add the line number for our current line to the output.
    Ada.Strings.Unbounded.Append(output, "|" & Integer'Image(Line_Number));

    -- Check to see if the label is not null so we can output it if it isn't.
    If Label /= "" Then

      Ada.Strings.Unbounded.Append(output, " " & Label & ":");
      Label_Flag := True;

    End If;

    -- Check to see if the original opcode is not null, so we can output it if it isn't.
    If Original_Opcode /= "" Then

      -- If we didn't have a label, then we need to space two tabs instead of the usual
      -- one for output.
      If Not Label_Flag Then
        Ada.Strings.Unbounded.Append(output, Character'Val(9));
      End If;

      Ada.Strings.Unbounded.Append(output, Character'Val(9) & Original_Opcode);
      Opcode_Flag := True;

    End If;

    -- Check to see if the original operand is not null, and if not we output it.
    If Original_Operand /= "" Then

      -- If we didn't have an opcode, then we need to space two tabs isntead of the usual
      -- one for output.
      If Not Opcode_Flag Then
        Ada.Strings.Unbounded.Append(output, Character'Val(9));
      End If;

      Ada.Strings.Unbounded.Append(output, Character'Val(9) & Original_Operand);
      Operand_Flag := True;

    End If;

    -- Check to see if we have a comment or not, if we do then we need to output it.
    If Comment /= "" Then

      If Opcode_Flag And Then Not Operand_Flag Then
        Ada.Strings.Unbounded.Append(output, Character'Val(9));
        Ada.Strings.Unbounded.Append(output, Character'Val(9));
        Ada.Strings.Unbounded.Append(output, " ");
      End If;

      Ada.Strings.Unbounded.Append(output, " " & Comment);

    End If;

    Return Ada.Strings.Unbounded.To_String(output);

  End Display_Line;

  -----
  -- Hex converter
  --
  -- Convert a base 10 number to hexidecimal
  --
  -- @access Private
  -----
  Function Int_To_Hex (Input : Integer) Return String is

    Result : Ada.Strings.Unbounded.Unbounded_String := Ada.Strings.Unbounded.Null_Unbounded_String;
    Current : Integer := Input;

    Function Convert (Num : Integer) Return Character is
    
      Char : Character;

    Begin

      If Num >= 10 Then
        Char := Character'Val(Num + 55);
      Elsif Num < 10 And Then Num >= 0 Then
        Char := Character'Val(Num + 48);
      End If;

      Return Char;

    End Convert;
    Pragma Inline (Convert);
    
    Position : Integer := 1;

  Begin

    Loop
    
      Exit When Current <= 15;

      Ada.Strings.Unbounded.Tail(Result, Position, Convert(Current MOD 16));
      Current := Current / 16;
      Position := Position + 1;

    End Loop;

    Ada.Strings.Unbounded.Tail(Result, Position, Convert(Current MOD 16));

    Return Ada.Strings.Unbounded.To_String(Result);

  End Int_To_Hex;

  -----
  -- Add a new page.
  --
  -- Adds a new page to the listing file.
  --
  -- @access Public
  -----
  Procedure New_Page (File_Handle : Ada.Text_IO.File_Type; Filename : String) is

  Begin

    Page_Number := Page_Number + 1;
    Ada.Text_IO.Put(File_Handle, "File: " & Filename & Character'Val(9) & Character'Val(9) & "date" & Character'Val(9) & Character'Val(9) & "Page ");
    Ada.Integer_Text_IO.Put(File_Handle, Page_Number, 0);
    Ada.Text_IO.New_Line(File_Handle, 2);

    Ada.Text_IO.Put_Line(File_Handle, "LINE OPCODE OPND  LOC    | LABEL" & Character'Val(9) & "OPCODE" & Character'Val(9) & "OPERAND");

  End New_Page;

  -----
  -- Add a new line
  --
  -- Adds a new line to the listing file.
  --
  -- @access Public
  -----
  Procedure New_Line (File_Handle   : Ada.Text_IO.File_Type;
                      Line_Number   : Integer;
                      Opcode_Value  : Integer;
                      Operand_Value : Integer;
                      Location      : Integer;
                      Label         : String;
                      Opcode        : String;
                      Operand       : String;
                      Comment       : String) is

    line : Ada.Strings.Unbounded.Unbounded_String := Ada.Strings.Unbounded.Null_Unbounded_String;

    Opcode_Flag   : Boolean := False;
    Operand_Flag  : Boolean := False;
    Label_Flag    : Boolean := False;

  Begin

    -- Pad output of line number
    If Line_Number < 10 And Then Line_Number >= 0 Then
      Ada.Strings.Unbounded.Append(line, "000");
    Elsif Line_Number >= 10 And Then Line_Number < 100 Then
      Ada.Strings.Unbounded.Append(line, "00");
    Elsif Line_Number >= 100 And Then Line_Number < 1000 Then
      Ada.Strings.Unbounded.Append(line, "0");
    End If;

    -- Output Line Number
    Declare
      Line_Number_String : String := Integer'Image(Line_Number);
    Begin
      Ada.Strings.Unbounded.Append(line, Line_Number_String(Line_Number_String'First + 1 .. Line_Number_String'Last) & " ");
    End;

    -- Output opcode value
    Declare
      Opcode_Value_String : String := Int_To_Hex(Opcode_Value);
    Begin
      If Opcode_Value >= 4 Then
        If Opcode_Value <= 9 Then
          Ada.Strings.Unbounded.Append(line, "   " & Opcode_Value_String & "  ");
        Else
          Ada.Strings.Unbounded.Append(line, "  " & Opcode_Value_String & "  ");
        End If;
      Elsif Opcode_Value = -1 Then
        Declare
          directive : String := Opcode(Opcode'First + 1 .. Opcode'Last);
        Begin
          If directive /= "WORD" or directive /= "EQU" or directive /= "BYTE" or directive /= "BLOCK" Then
            Ada.Strings.Unbounded.Append(line, "      ");
          End If;
        End;
      Else
        Ada.Strings.Unbounded.Append(line, "      ");
      End If;
    End;


    Declare
      Opcode_Value_String : String := Integer'Image(Opcode_Value);
    Begin
      If Opcode_Value = -1 Then
        Declare
          directive : String := Opcode(Opcode'First + 1 .. Opcode'Last);
        Begin
          If directive /= "WORD" or directive /= "EQU" or directive /= "BYTE" or directive /= "BLOCK" Then
            Ada.Strings.Unbounded.Append(line, "      ");
          End If;
        End;
      Else
        Ada.Strings.Unbounded.Append(line, "      ");
      End If;
    End;

    Declare
      Location_String : String := Int_To_Hex(Location);
    Begin
      Ada.Strings.Unbounded.Append(line, " ");

      Case (Location_String'Length) is
        When 0 =>
          Ada.Strings.Unbounded.Append(line, "000000");
        When 1 =>
          Ada.Strings.Unbounded.Append(line, "00000" & Location_String);
        When 2 =>
          Ada.Strings.Unbounded.Append(line, "0000" & Location_String);
        When 3 =>
          Ada.Strings.Unbounded.Append(line, "000" & Location_String);
        When 4 =>
          Ada.Strings.Unbounded.Append(line, "00" & Location_String);
        When 5 =>
          Ada.Strings.Unbounded.Append(line, "0" & Location_String);
        When 6 =>
          Ada.Strings.Unbounded.Append(line, Location_String);
        When Others =>
          null;
      End Case;

      Ada.Strings.Unbounded.Append(line, " ");
    End;

    -- Add the line number for our current line to the line.
    Ada.Strings.Unbounded.Append(line, "|");

    -- Check to see if the label is not null so we can line it if it isn't.
    If Label /= "" Then

      Ada.Strings.Unbounded.Append(line, " " & Label & ":");
      Label_Flag := True;

    End If;

    -- Check to see if the original opcode is not null, so we can line it if it isn't.
    If Opcode /= "" Then

      -- If we didn't have a label, then we need to space two tabs instead of the usual
      -- one for line.
      If Not Label_Flag Then
        Ada.Strings.Unbounded.Append(line, Character'Val(9));
      End If;

      Ada.Strings.Unbounded.Append(line, Character'Val(9) & Opcode);
      Opcode_Flag := True;

    End If;

    -- Check to see if the original operand is not null, and if not we line it.
    If Operand /= "" Then

      -- If we didn't have an opcode, then we need to space two tabs isntead of the usual
      -- one for line.
      If Not Opcode_Flag Then
        Ada.Strings.Unbounded.Append(line, Character'Val(9));
      End If;

      Ada.Strings.Unbounded.Append(line, Character'Val(9) & Operand);
      Operand_Flag := True;

    End If;

    -- Check to see if we have a comment or not, if we do then we need to line it.
    If Comment /= "" Then

      If Opcode_Flag And Then Not Operand_Flag Then
        Ada.Strings.Unbounded.Append(line, Character'Val(9));
        Ada.Strings.Unbounded.Append(line, Character'Val(9));
        Ada.Strings.Unbounded.Append(line, " ");
      End If;

      Ada.Strings.Unbounded.Append(line, " " & Comment);

    End If;

    Ada.Text_IO.Put_Line(File_Handle, Ada.Strings.Unbounded.To_String(line));

  End New_Line;

  -----
  -- Add a new error line
  --
  -- Adds a new line to the listing file containing the error of the line above it.
  --
  -- @access Public
  -----
  Procedure New_Error (File_Handle : Ada.Text_IO.File_Type; Error_Message : String) is

  Begin

    null;

  End New_Error;

  -----
  -- Label table header
  --
  -- Output the label table header to the file.
  --
  -- @access Public
  -----
  Procedure Label_Header (File_Handle : Ada.Text_IO.File_Type) is

  Begin

    Ada.Text_IO.New_Line(File_Handle, 2);
    Ada.Text_IO.Put_Line(File_Handle, "Lable" & Character'Val(9) & Character'Val(9) & "Value");

  End Label_Header;

  -----
  -- New Label
  --
  -- Append a new label to the label table
  --
  -- @access Public
  -----
  Procedure New_Label (File_Handle : Ada.Text_IO.File_Type; Label : String; Value : Integer) is

  Begin

    Ada.Text_IO.Put_Line(File_Handle, Label & Character'Val(9) & Character'Val(9) & Int_To_Hex(Value));

  End New_Label;

End output;
