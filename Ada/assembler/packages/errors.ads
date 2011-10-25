With Ada.Strings.Unbounded;
With Ada.Text_IO;

-----
-- Error Table
--
-- Handle the errors for each line.
--
-- @access Public
-- @author Matt Emborsky <mlemborsky01@indianatech.net>
-----
Package Errors is

  -----
  -- Is_Empty
  --
  -- Check if the error table is empty or not. If it is then we don't have any errors.
  --
  -- @access Public
  -----
  Function Check_For_Errors Return Boolean;

  -----
  -- Append Error
  --
  -- Add an error to the error table with the given Line_Number and Error String.
  --
  -- @access Public
  -----
  Procedure Append (Line_Number : Integer; Error_Message : String);

  -----
  -- Print out Errors
  --
  -- This function will print out our error messages to the listing file.
  --
  -- @access Public
  -----
  Procedure Put_Errors (Output : Ada.Text_IO.File_Type; Line_Number : Integer);
  Procedure Put_Errors (Line_Number : Integer);

Private

  -----
  -- Error Table
  --
  -- Hold errors in list form.
  --
  -- @access Public
  -----
  Type Errors;

  -----
  -- Pointer
  --
  -- Points to our next "node" in the list.
  --
  -- @access Private
  -----
  Type Pointer is Access Errors;

  -----
  -- Error Table
  --
  -- Hold our errors in linked list form.
  --
  -- @access Private
  -----
  Type Errors is Record
    Line_Number   : Integer;
    Error_Message : Ada.Strings.Unbounded.Unbounded_String;
    Next          : Pointer;
  End Record;

End Errors;
