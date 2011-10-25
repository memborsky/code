With Ada.Text_IO;
Use Ada.Text_IO;
Package numstring_package IS

--    Procedure calculation;
	-- Procedure will do all calculations.

    Procedure put_data(file : in out File_Type);
	-- Procedure will output the data to the screen, all data output is through this procedure.

    Procedure get_data(file : in out File_Type);
	-- Procedure will get all the data, this is the only page to get data.

End numstring_package;
