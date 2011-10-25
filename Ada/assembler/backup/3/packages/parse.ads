With Ada.Strings.Unbounded;
With Ada.Text_IO;
With Ada.Command_Line;

-----
-- Parse various needs to the assembler.
--
-- This package will parse multiple items for our assember. This includes the command line and the actually
-- assembly file that we are assembling. We even parse the operand to get the type of operand we have.
--
-- @access Public
-- @author Matt Emborsky <mlemborsky01@indianatech.net>
-----
Generic

  -- This is used to define what character we are using to destinguish our label from the rest of the line.
  Label_Seperator : Character;

  -- This is used to define how our comments are starting.
  Comment_Label   : Character;

-----
-- Parse various needs to the assembler.
--
-- This package will parse multiple items for our assember. This includes the command line and the actually
-- assembly file that we are assembling. We even parse the operand to get the type of operand we have.
--
-- @access Public
-- @author Matt Emborsky <mlemborsky01@indianatech.net>
-----
Package Parse is

  -- This is our type decleration to our line data.
  Type Line is Private;

  -- This function will parse the command line and return the filename of our file we are wanting to assemble.
  Function Command_Line Return String;

  -- This will parse our line, given that we have gotten the line from the file and are sending it into this function.
  -- We return a pointer reference to the location of our Line data type.
  Function Parse (sLine : String) Return Line;

  -- Count the number of commas in the string.
  Function Count_Commas (temp : String) Return Integer;

  -- Pull the opcode out of the line reference and return it as a string.
  Function Get_Opcode (Data : Line) Return String;

  -- Pull the operand out of the line refrence and return it as a string.
  Function Get_Operand (Data : Line) Return String;

  -- Pull the label out of the line reference and return it as a string.
  Function Get_Label (Data : Line) Return String;

  -- Pull the comment out of the line reference and return it as a string.
  Function Get_Comment (Data : Line) Return String;

  -- Find the basename of our filename
  Function Basename (filename : String; extension : String := "") Return String;

  -- Define our inline functions for the package.
  Pragma Inline (Get_Opcode);
  Pragma Inline (Get_Operand);
  Pragma Inline (Get_Comment);
  Pragma Inline (Get_Label);
  Pragma Inline (Basename);

  -----
  -- Macro Pre-Processor
  --
  -- This subpackage is used to expand our macros and handle our macro table expansions.
  --
  -- @access Public
  -----
  Generic

    -- Token to flag for each parameter.
    Token : Character;

    -- Handles the size of the parameter list.
    Max_Number_of_Parameters : Integer;

    -- Handles the line length of the macro.
    Max_Macro_Length : Integer;

  Package Macro is

    -- Returns true if the macro name is a defined macro table.
    Function Is_Macro (Macro_Name : String) Return Boolean;
    Pragma Inline (Is_Macro);

    -- Append a new macro to the macro table.
    Procedure Append (Macro_Name : String; Formal_Parameter_List : String; File_Handle : Ada.Text_IO.File_Type);

    -- Expand our Macros
    Procedure Expand (Macro_Name : String; Actual_Parameter : String; File_Handle : Ada.Text_IO.File_Type);

    -- Invalid Macro Name exception.
    INVALID_MACRO_NAME : Exception;

  Private

    -- Holds pointers to all the pieces of the macro as well as the next macro in the list.
    Type Macro_Table;

    -- Pointers to required pieces of data in the macro table.
    Type Pointer_Macro_Table is Access Macro_Table;

    -- Holds the lines of the actual macro tokenized with our token.
    Type Macro_Data_Table is Array(0..Max_Macro_Length) of Ada.Strings.Unbounded.Unbounded_String;

    -- Holds the parameters of the formal parameter list in natural form.
    Type Parameter_Array is Array(0..12) of Ada.Strings.Unbounded.Unbounded_String;

    -- This holds all our current macros and the location at a pointer to which to find them.
    Type Macro_Table is Record
      Name    : Ada.Strings.Unbounded.Unbounded_String;
      Formal  : Parameter_Array;
      Lines   : Macro_Data_Table;
      Next    : Pointer_Macro_Table;
    End Record;

  End Macro;


  -- This exception is raised if the program has no argument passed into it.
  No_File_Argument : Exception;



Private

  -- This is our record to hold our line data. We implement this using an array in the main program to keep references of this record.
  Type Line is Record
    label   : Ada.Strings.Unbounded.Unbounded_String := Ada.Strings.Unbounded.Null_Unbounded_String;
    opcode  : Ada.Strings.Unbounded.Unbounded_String := Ada.Strings.Unbounded.Null_Unbounded_String;
    operand : Ada.Strings.Unbounded.Unbounded_String := Ada.Strings.Unbounded.Null_Unbounded_String;
    comment : Ada.Strings.Unbounded.Unbounded_String := Ada.Strings.Unbounded.Null_Unbounded_String;
  End Record;


End Parse;
