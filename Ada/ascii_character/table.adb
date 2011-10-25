With Ada.Text_IO;
With Ada.Integer_Text_IO;

Procedure Table Is

Begin

  For index in 32 .. 126 Loop
    Ada.Text_IO.put(Character'Val(index) & "; ");
    Ada.Integer_Text_IO.put(index, 0);
    Ada.Text_IO.put(Character'Val(9));
    If (index - 31) MOD 6 = 0 Then
      Ada.Text_IO.new_line;
    End If;
  End Loop;

End Table;
