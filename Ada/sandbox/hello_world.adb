With Ada.Text_IO;
Use Ada.Text_IO;

Procedure Hello_World is

Begin

  -- This is the method of usage if you do not add `Use Ada.Text_IO;`
  Ada.Text_IO.Put_Line("Hello World 1!");

  -- This is only allowed if you add `Use Ada.Text_IO;` to the package decleration.
  Put_Line("Hello World 2!");

End Hello_World;
