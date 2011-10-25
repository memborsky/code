With Ada.Text_IO;

-----
-- Handle our output.
--
-- This package will take care of all our output control and allow us to centralize the output for
-- generic package use in the main assembler program.
--
-- @package output
-- @access  public
-- @author  Matt Emborsky <mlemborsky01@indianatech.net>
-----
Package output is

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
  Return String;

  -----
  -- Add a new page.
  --
  -- Adds a new page to the listing file.
  --
  -- @access Public
  -----
  Procedure New_Page (File_Handle : Ada.Text_IO.File_Type; Filename : String);

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
                      Comment       : String);

  -----
  -- Add a new error line
  --
  -- Adds a new line to the listing file containing the error of the line above it.
  --
  -- @access Public
  -----
  Procedure New_Error (File_Handle : Ada.Text_IO.File_Type; Error_Message : String);

  -----
  -- Label table header
  --
  -- Output the label table header to the file.
  --
  -- @access Public
  -----
  Procedure Label_Header (File_Handle : Ada.Text_IO.File_Type);

  -----
  -- New Label
  --
  -- Append a new label to the label table
  --
  -- @access Public
  -----
  Procedure New_Label (File_Handle : Ada.Text_IO.File_Type; Label : String; Value : Integer);

End output;
