With Ada.Text_IO;
With Ada.Strings.Unbounded;
With Ada.Streams.Stream_IO;
With Ada.Command_Line;

Use Type Ada.Streams.Stream_IO.Count;

Procedure mp3_id3_reader Is

    Type Tag_Data_Record Is Record
        Title   : Ada.Strings.Unbounded.Unbounded_String;
        Artist  : Ada.Strings.Unbounded.Unbounded_String;
        Album   : Ada.Strings.Unbounded.Unbounded_String;
        Year    : Ada.Strings.Unbounded.Unbounded_String;
        Comment : Ada.Strings.Unbounded.Unbounded_String;
        Genre   : Ada.Strings.Unbounded.Unbounded_String;
    End Record;

    File   : Ada.Streams.Stream_IO.File_Type;

    Buffer : String(1..128);

Begin

    If Ada.Command_Line.Argument_Count >= 1 Then

        For Argument In 1..Ada.Command_Line.Argument_Count Loop

            Declare

                -- This holds our current file name from the arugment list.
                Filename : String renames Ada.Command_Line.Argument(Argument);

            Begin
                -- Opens our current file paramter for tag data parsing.
                Ada.Streams.Stream_IO.Open(File, Ada.Streams.Stream_IO.In_File, Filename);

                -- DEBUG: Output our current file name so we can see what we are working with.
                Ada.Text_IO.Put_Line("Filename = " & Filename);

                -- Move the file pointer to 128 bytes from the end of the file.
                Ada.Streams.Stream_IO.Set_Index(File, Ada.Streams.Stream_IO.Size(File) - 128); 

                -- This will read the input stream and dump it into our buffer which we will parse later for tag data.
                String'Read(Ada.Streams.Stream_IO.Stream(File), Buffer);

                -- DEBUG: Just puts our buffer string so we can see what we got.
                Ada.Text_IO.Put_Line(Buffer);

                -- Close the file stream.
                Ada.Streams.Stream_IO.Close(File);

                -- Seperate our current file's output data before the next.
                Ada.Text_IO.New_Line(1);
            End;

        End Loop;

    End If;

End mp3_id3_reader;
