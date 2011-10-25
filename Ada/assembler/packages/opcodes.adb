With Ada.Text_IO;
With Ada.Integer_Text_IO;

Package Body Opcodes is

  -- Local to this package this array contains all the opcodes in it. We will use
  -- data hiding to limit the ability for the outside user to manipulate the data
  -- of the array after we initialize it in the elaboration section below.
  Opcodes_Array : Opcodes;
  Directives_Array : Directives;


  --
  -- To_String - Returns string of the opcode sent in.
  --
  -- @Opcode
  Function To_String (Opcode : Opcode_Range) Return String is

  Begin

    Return Opcode_Range'Image(Opcode);

  End To_String;

  --
  -- To_String - Returns string of the directive sent in.
  --
  -- @Directive
  Function To_String (Directive : Directive_Range) Return String is

  Begin

    Return Directive_Range'Image(Directive);

  End To_String;

  --
  -- To_Integer - Returns the integer value of the opcode sent in.
  --
  -- @Opcode
  Function To_Integer (Opcode : Opcode_Range) Return Integer is

  Begin

    Return Opcodes_Array(Opcode);

  End To_Integer;

  --
  -- To_Integer - Returns the integer value of the directive sent in.
  --
  -- @Directive
  Function To_Integer (Directive : Directive_Range) Return Integer is

  Begin

    Return Directives_Array(Directive);

  End To_Integer;

  -- Handle for initializing opcode array.
  File_Handle : Ada.Text_IO.File_Type;

Begin

  Ada.Text_IO.Open(File_Handle, Ada.Text_IO.IN_File, Filename);

  For Current_Opcode in Opcode_Range'First .. Opcode_Range'Last Loop
    Ada.Integer_Text_IO.Get(File_Handle, Opcodes_Array(Current_Opcode));
  End Loop;

  Ada.Text_IO.Close(File_Handle);

  For Current_Directive in Directive_Range'First .. Directive_Range'Last Loop
    Directives_Array(Current_Directive) := -1;
  End Loop;

End Opcodes;
