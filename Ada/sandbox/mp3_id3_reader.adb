with Ada.Text_IO;
with Ada.Strings.Unbounded;
with Ada.Streams.Stream_IO;
with Ada.Command_Line;
with Ada.Streams;

use type Ada.Streams.Stream_IO.Count;

procedure mp3_id3_reader is

    -- Holds our file stream pointer.
    File : Ada.Streams.Stream_IO.File_Type;

    -- Holds our tag data from the file we pass in via argument list.
    Tag : Ada.Streams.Stream_Element_Array(1..4000);

    -- This is the location in our tag array that our last piece of data was read into.
    Last : Ada.Streams.Stream_Element_Offset;

    -- Used to offset ourselves from the beginning of the file to reading our tag.
    Offset : Ada.Streams.Stream_IO.Count;

begin

    -- Only proceed if we actually have an argument.
    if Ada.Command_Line.Argument_Count >= 1 then

        -- Loop through each argument we are passed from the command line.
        for Argument in 1..Ada.Command_Line.Argument_Count loop

            declare

                -- Our current arguments filename.
                Filename : String renames Ada.Command_Line.Argument(Argument);

            begin
                -- Opens our current file paramter for tag data parsing.
                Ada.Streams.Stream_IO.Open(File, Ada.Streams.Stream_IO.in_File, Filename);

                -- DEBUG: Output our current file name so we can see what we are working with.
                Ada.Text_IO.Put_Line("Filename = " & Filename);

                -- Make sure we are at the beginning of the file stream before we attempt to read.
                Ada.Streams.Stream_IO.Set_index(File, 1);

                -- We want to make sure we are 128 bits from the end of our file for the id3 tag to be read.
                Offset := Ada.Streams.Stream_IO.Size(File) - 128;

                -- Read the input stream and dump it into our tag array.
                Ada.Streams.Stream_IO.Read(File, Tag, Last, Offset);

                -- DEBUG: This will convert our tag stream element into a string and ouput it to the console.
                declare
                    Output : String (1 .. Positive(Last));
                begin
                    for Index in Output'Range loop
                        Output(Index) := Character'Val( Natural( Tag( Ada.Streams.Stream_Element_Offset(Index) ) ) );
                    end loop;

                    Ada.Text_IO.Put_Line(Output);
                end;

                -- Close the file stream.
                Ada.Streams.Stream_IO.Close(File);

                -- Seperate our current file's output data before the next.
                Ada.Text_IO.New_Line(1);
            end;

        end loop;

    else

        -- Output our command usage.
        Ada.Text_IO.Put_Line("Command Usage: mp3_id3_reader <path/to/file.mp3>");

    end if;

end mp3_id3_reader;
