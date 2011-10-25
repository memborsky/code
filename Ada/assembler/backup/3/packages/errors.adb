-----
-- Error Table
--
-- Handle the errors for each line.
--
-- @access Public
-- @author Matt Emborsky <mlemborsky01@indianatech.net>
-----
Package Body Errors is

  -----
  -- Holds the head pointer for the error list
  --
  -- @access  Private
  -- @var     Pointer (or Node of the next linked list)
  -----
  Head : Pointer := Null;

  -----
  -- Is_Empty
  --
  -- Check if the error table is empty or not. If it is then we don't have any errors.
  --
  -- @access Public
  -----
  Function Check_For_Errors Return Boolean is

  Begin

    If Head = Null Then
      Return True;
    Else
      Return False;
    End If;

  End Check_For_Errors;

  -----
  -- Append Error
  --
  -- Add an error to the error table with the given Line_Number and Error String.
  --
  -- @access Public
  -----
  Procedure Append (Line_Number : Integer; Error_Message : String) is

    New_Pointer : Pointer := Null;

  Begin

    New_Pointer := New Errors'(Line_Number, Ada.Strings.Unbounded.To_Unbounded_String(Error_Message), Head);

    Head := New_Pointer;

  End Append;

  -----
  -- Print out Errors
  --
  -- This function will print out our error messages to the listing file.
  --
  -- @access Public
  -----
  Procedure Put_Errors (Output : Ada.Text_IO.File_Type; Line_Number : Integer) is

    Scan : Pointer := Head;

  Begin

    Loop

      If Scan.Line_Number = Line_Number Then
        Ada.Text_IO.Put_Line(Output, Ada.Strings.Unbounded.To_String(Scan.Error_Message));
      End If;

      Exit When Scan.Next = Null;
      Scan := Scan.Next;

    End Loop;

  End Put_Errors;

  -----
  -- Print out Errors
  --
  -- This function will print out our error messages to the listing file.
  --
  -- @access Public
  -----
  Procedure Put_Errors (Line_Number : Integer) is

    Scan : Pointer := Head;

  Begin

    If Scan /= Null Then

      Loop

        If Scan.Line_Number = Line_Number Then
          Ada.Text_IO.Put_Line(Ada.Strings.Unbounded.To_String(Scan.Error_Message));
        End If;

        Exit When Scan.Next = Null;
        Scan := Scan.Next;

      End Loop;

    End If;

  End Put_Errors;

End Errors;
