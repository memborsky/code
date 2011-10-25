With Ada.Text_IO;
With Ada.Integer_Text_IO;

Generic

  Type Opcode_Range is (<>);
  Type Directive_Range is (<>);
  Filename : String;

Package Opcodes Is

  Type Opcodes is Limited Private;
  Type Directives is Limited Private;

  Function To_String (Opcode : Opcode_Range) Return String;
  Function To_String (Directive : Directive_Range) Return String;

  Function To_Integer (Opcode : Opcode_Range) Return Integer;
  Function To_Integer (Directive : Directive_Range) Return Integer;

Private

  Type Opcodes is Array(Opcode_Range) of Integer;
  Type Directives is Array (Directive_Range) of Integer;

End Opcodes;
