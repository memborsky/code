With Ada.Text_IO;
With GNAT.MD5;
With Ada.Command_Line;
With Ada.Strings.Unbounded;

Procedure gravatar Is

    email       : Ada.Strings.Unbounded.Unbounded_String := Ada.Strings.Unbounded.To_Unbounded_String("");
    
    uri         : String := "https://secure.gravatar.com/avatar/";
    size        : Integer := 140;

    Function Get_Integer_As_String (Input : Integer) Return String Is
    
        Result : String := Integer'Image(Input);

    Begin
    
        Return Result(Result'First + 1 .. Result'Last);
        
    End Get_Integer_As_String;

Begin

    If Ada.Command_Line.Argument_Count = 1 Then
        email := Ada.Strings.Unbounded.To_Unbounded_String(Ada.Command_Line.Argument(1));
    End If;
        
    Ada.Text_IO.Put_Line(uri & GNAT.MD5.Digest(Ada.Strings.Unbounded.To_String(email)) & "?s=" & Get_Integer_As_String(size));

End gravatar;

