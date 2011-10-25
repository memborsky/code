With Ada.Text_IO, Ada.Integer_Text_IO, Ada.Text_IO.Editing;
Use Ada.Text_IO, Ada.Integer_Text_IO;
Package BODY numstring_package IS

    -- data declerations
    Type ar IS Array (1 .. 20) OF Character;
    Type list IS Record
	char : ar;
	int : integer;
	length : Integer;
      End Record;
    Type dat IS Array (1 .. 2) OF list;
    data : dat;
    oper : Character;
    Type MyNum is delta 1.0 digits 18;
	Package DecOut is new Ada.Text_IO.Editing.Decimal_Output (MyNum);
    error : Boolean := False;
    error_data : Character;

------ Convert data
    Procedure convert_data IS

	local_data : Integer := 1;
	local_char : Integer := 1;
	integ : Integer := 0;

    Begin

	While local_data <= 2 Loop
	    While local_char <= data(local_data).length Loop
		integ := (integ * 10) + (Character'Pos(data(local_data).char(local_char)) - 48);
		Ada.Integer_Text_IO.put(integ);
		local_char := local_char + 1;
	    End Loop;
	    data(local_data).int := integ;
	    local_data := local_data + 1;
	    local_char := 1;
	    Ada.Text_IO.new_line;
	    integ := 0;
	End Loop;
    End convert_data;

------ Package Procedures and Functions
    Function calculation(operation : in Character; num_1 : in Integer; num_2 : in Integer) Return Integer IS
	result : integer;

	Function add (num_1 : Integer; num_2 : Integer) Return Integer IS
	result : Integer;
	Begin
		result := num_1 + num_2;
		Return result;

	End add;

	Function subtract (num_1 : Integer; num_2 : Integer) Return Integer IS
	result : Integer;
	Begin
	    result := num_1 - num_2;
	    Return result;
	End subtract;

	Function divide (num_1 : Integer; num_2 : Integer) Return Integer IS
	result : Integer;
	Begin
	    result := num_1 / num_2;
	    Return result;
	End divide;

	Function multiple (num_1 : Integer; num_2 : Integer) Return Integer IS
	result : Integer;
	Begin
	    result := num_1 * num_2;
	    Return result;
	End multiple;

    Begin
	If operation = '+' Then
	    result := add(num_1, num_2);
	Elsif operation = '-' Then
	    result := subtract(num_1, num_2);
	Elsif operation = '/' Then
	    result := divide(num_1, num_2);
	Elsif operation = '*' Then
	    result := multiple(num_1, num_2);
	End If;
	return result;
    End calculation;

------ Validate data
    Function validate (process : in Character; info : in Character; num : Integer) Return Boolean IS
	result : Boolean;

	Function char (info : in Character) Return Boolean IS
	result : Boolean;
	Begin
	    IF info in '0' .. '9' OR info = '.' Then
		result := True;
		If info = '.' Then
		    data(num).char(1) := '*';
		End If;
	    Else
		result := False;
		error_data := info;
	    End IF;
	    Return result;
	End char;

	Function plus_minus (info : in Character) Return Boolean IS
	result : Boolean;
	Begin
	    IF info = '-' OR info in '0' .. '9' Then
		result := True;
	    Else
		result := False;
	    End IF;
	    Return result;
	End plus_minus;

	Function operation (info : in Character) Return Boolean IS
	result : Boolean;
	Begin
	    If info = '-' OR info = '+' OR info = '/' OR info = '*' Then
		result := True;
	    Else
		result := False;
	    End IF;
	    Return result;
	End operation;

	Begin
	    If process = 'c' Then
		result := char(info);
	    Elsif process = 'p' Then
		result := plus_minus(info);
	    Elsif process = 'o' Then
		result := operation(info);
	    End IF;
	Return result;
    End validate;

------ Procedure to output data
    Procedure put_data (file : in out File_Type) IS
	local_data : Integer := 1;
	local_char : Integer := 1;
    Begin
	While local_char <= data(local_data).length Loop
	    Ada.Text_IO.put(file, data(local_data).char(local_char));
	    local_char := local_char + 1;
	End Loop;
	Ada.Text_IO.put(file, ' ' & oper & ' ');
	local_char := 1;
	local_data := 2;
	While local_char <= data(local_data).length Loop
	    Ada.Text_IO.put(file, data(local_data).char(local_char));
	    local_char := local_char + 1;
	End Loop;
	Ada.Text_IO.put(file, ' ' & '=' & ' ');
	If data(1).char(1) = '*' OR data(2).char(1) = '*' Then
	    Ada.Text_IO.put("Floating point calculations incomplete!");
	Else
	    convert_data;
	    Ada.Integer_Text_IO.put(data(1).int, 1);
	    Ada.Text_IO.new_line;
	    Ada.Integer_Text_IO.put(file, calculation(oper, data(1).int, data(2).int), 1);
	    Ada.Text_IO.new_line(file);
	End If;
    End put_data;



------ Procedure to get data
    Procedure get_data (file : in out File_Type) IS
	local_data : Integer := 1;
	local_char : Integer := 1;
	valid : Boolean := True;
	null_char : Character;
    Begin
	Ada.Text_IO.get(file, data(local_data).char(local_char));
	valid := validate('p', data(local_data).char(local_char), local_data);
	local_char := local_char + 1;
	IF valid Then
	    While data(local_data).char(local_char - 1) /= ' ' Loop
		Ada.Text_IO.get(file, data(local_data).char(local_char));
		valid := validate('c', data(local_data).char(local_char), local_data);
		If valid Then
		    local_char := local_char + 1;
		Elsif not valid Then
		    error := True;
		End IF;
		Exit When error;
	    End Loop;
	    data(local_data).length := local_char - 1;
	End If;
	Ada.Text_IO.get(file, oper);
	valid := validate('o', oper, local_data);
	If valid Then
	    local_data := 2;
	    local_char := 1;
	Else
	    error := true;
	End IF;
	Ada.Text_IO.get(file, null_char);
	Ada.Text_IO.get(file, data(local_data).char(local_char));
	valid := validate('p', data(local_data).char(local_char), local_data);
	local_char := local_char + 1;
	If valid Then
	    While not Ada.Text_IO.end_of_line(file) Loop
		Ada.Text_IO.get(file, data(local_data).char(local_char));
		valid := validate('c', data(local_data).char(local_char), local_data);
		If valid Then
		    local_char := local_char + 1;
		Elsif not valid Then
		    error := True;
		End IF;
		Exit When error;
	    End Loop;
	    data(local_data).length := local_char;
	    Ada.Text_IO.get(file, data(local_data).char(data(local_data).length));
	End IF;
    End get_data;
End numstring_package;
