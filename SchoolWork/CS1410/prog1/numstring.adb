With Ada.Text_IO, numstring_package;
Procedure numstring IS

	-- file declerations
	input : Ada.Text_IO.File_Type;
	output : Ada.Text_IO.File_Type;

Begin

    -- Open both input and output files
	Ada.Text_IO.open(input, Ada.Text_IO.in_file, "input.txt");
	Ada.Text_IO.open(output, Ada.Text_IO.out_file, "output.txt");


    -- Main Program execution
     While not Ada.Text_IO.end_of_file(input) Loop
	numstring_package.get_data(input);
	numstring_package.put_data(output);
     End Loop;

    -- Close both input and output files
	Ada.Text_IO.close(input);
	Ada.Text_IO.close(output);

End numstring;
