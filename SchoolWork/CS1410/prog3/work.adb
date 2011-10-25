With Ada.Text_IO, Ada.Integer_Text_IO, Ada.Float_Text_IO, Stacks;
Use Ada.Text_IO;

Procedure work IS

num, num_1, num_2 : Float;
sign, op_sign : Character;
end_prog : Boolean := False;

Type array_size IS Range 1..100;
Package num_stacky IS NEW Stacks (Size => array_size, Element_Type => Float);
num_stack : num_stacky.Stack;
Package sign_stacky IS NEW Stacks (Size => array_size, Element_Type => Character);
sign_stack : sign_stacky.Stack;

Function add(val_1, val_2 : Float) Return Float IS

    Begin
    	Return (val_1 + val_2);
    End add;

Function subtract(val_1, val_2 : Float) Return Float IS

    Begin
    	Return (val_1 - val_2);
    End subtract;

Function divide(val_1, val_2 : Float) Return Float IS

    Begin
    	Return (val_1 / val_2);
    End divide;

Function multiple(val_1, val_2 : Float) Return Float IS

    Begin
    	Return (val_1 * val_2);
    End multiple;

Function calculate(sign : Character; val_1, val_2 : Float) Return Float IS

  answer : Float := 0.0;

    Begin
	IF sign = '+' Then
	    answer := add(val_1, val_2);
	Elsif sign = '-' Then
	    answer := subtract(val_1, val_2);
	Elsif sign = '*' Then
	    answer := multiple(val_1, val_2);
	Elsif sign = '/' Then
	    answer := divide(val_1, val_2);
	End IF;
	Return answer;
    End calculate;

Function sign_val (sign_1, sign_2 : Character) Return Boolean IS
	result : Integer := 0;
	Type res_arr IS Array(1..2) OF Integer;
	results : res_arr;
	final : Boolean;
	Begin
	    IF sign_1 = '-' OR sign_1 = '+' Then
		result := 1;
	    Elsif sign_1 = '*' OR sign_1 = '/' Then
		result := 2;
	    Elsif sign_1 = ';' Then
		result := 0;
	    End IF;
	    results(1) := result;
	    IF sign_2 = '-' OR sign_2 = '+' Then
		result := 1;
	    Elsif sign_2 = '*' OR sign_2 = '/' Then
		result := 2;
	    Elsif sign_2 = ';' Then
		result := 0;
	    End IF;
	    results(2) := result;
	    IF results(1) < results(2) Then
		final := True;
	    Else
		final := False;
	    End IF;
	    Return final;
	End sign_val;

Function sign_val_2 (sign_1, sign_2 : Character) Return Boolean IS
	result : Integer := 0;
	Type res_arr IS Array(1..2) OF Integer;
	results : res_arr;
	final : Boolean;
	Begin
	    IF sign_1 = '-' OR sign_1 = '+' Then
		result := 1;
	    Elsif sign_1 = '*' OR sign_1 = '/' Then
		result := 2;
	    Elsif sign_1 = ';' Then
	    	result := 0;
	    End IF;
	    results(1) := result;
	    IF sign_2 = '-' OR sign_2 = '+' Then
		result := 1;
	    Elsif sign_2 = '*' OR sign_2 = '/' Then
		result := 2;
	    Elsif sign_2 = ';' Then
	    	result := 0;
	    End IF;
	    results(2) := result;
	    IF results(1) > results(2) Then
		final := True;
	    Else
		final := False;
	    End IF;
	    Return final;
	End sign_val_2;

Begin

    While Not Ada.Text_IO.end_of_file Loop

	num_stacky.initialize(num_stack);
	sign_stacky.initialize(sign_stack);

	Loop
	    Ada.Float_Text_IO.get(num);
		IF num = 0.0 Then
		   end_prog := True;
		End IF;
	Exit When end_prog;
	    num_stacky.push(num_stack, num);
	    Ada.Text_IO.get(sign);
	    Ada.Text_IO.get(sign);
	    IF sign_stacky.empty(sign_stack) OR sign_val(sign, sign_stacky.peek(sign_stack)) OR sign = ';' Then
		Loop
		    sign_stacky.pop(sign_stack, op_sign);
		    num_stacky.pop(num_stack, num_1);
		    num_stacky.pop(num_stack, num_2);
		    num_stacky.push(num_stack, calculate(op_sign, num_1, num_2));
		  Exit When sign_stacky.empty(sign_stack) OR sign_val_2(op_sign, sign_stacky.peek(sign_stack));
		End Loop;	    
	    End IF;
	    Exit When sign = ';';
	    sign_stacky.push(sign_stack, sign);
	End Loop;
	
	IF NOT sign_stacky.empty(sign_stack) OR NOT end_prog Then
	    Loop
		num_stacky.pop(num_stack, num_2);
		sign_stacky.pop(sign_stack, op_sign);
		num_stacky.pop(num_stack, num_1);
		num_stacky.push(num_stack, calculate(op_sign, num_1, num_2));
	      Exit When sign_stacky.empty(sign_stack);
	    End Loop;
	End IF;
    Exit When end_prog;
	num_stacky.pop(num_stack, num_1);
	Ada.Float_Text_IO.put(num_1, 3, 3, 0);
	Ada.Text_IO.new_line;
    End Loop;

End work;
