With Ada.Text_IO, list;
Procedure main IS

first_name : string(1..20);
first_name_length : integer;
last_name : string(1..20);
last_name_length : integer;


Begin

	Ada.Text_IO.put("Please Input a First Name - ");
	Ada.Text_IO.get(first_name, first_name_length);
	Ada.Text_IO.put("Please Input a Last Name - ");
	Ada.Text_IO.get(last_name, last_name_length);

End main;