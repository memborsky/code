With Ada.Strings.Unbounded;
With Ada.Text_IO;

procedure unbounded_trim is

  test : Ada.Strings.Unbounded.Unbounded_String := Ada.Strings.Unbounded.To_Unbounded_String("foo/bar/please/work");

begin

  Ada.Text_IO.Put_Line(Ada.Strings.Unbounded.To_String(Ada.Strings.Unbounded.Delete(test, 1, Ada.Strings.Unbounded.Index(test, "/"))));

end unbounded_trim;
