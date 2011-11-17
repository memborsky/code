with Ada.Text_IO;
with GNAT.MD5;
with Ada.Command_Line;
with Ada.Strings.Unbounded;

procedure gravatar is

    uri         : String := "https://secure.gravatar.com/avatar/";
    size        : Integer := 140;

    function Get_Integer_As_String (Input : Integer) return String is

        Result : String := Integer'Image(Input);

    begin

        return Result(Result'First + 1 .. Result'Last);

    end Get_Integer_As_String;

begin

    if Ada.Command_Line.Argument_Count >= 1 then

        for Argument In 1..Ada.Command_Line.Argument_Count loop

            declare

                email : Ada.Strings.Unbounded.Unbounded_String := Ada.Strings.Unbounded.To_Unbounded_String(Ada.Command_Line.Argument(Argument));

            begin

                Ada.Text_IO.Put_Line(uri & GNAT.MD5.Digest(Ada.Strings.Unbounded.To_String(email)) & "?s=" & Get_Integer_As_String(size));

            end;

        end loop;
    end if;


end gravatar;

