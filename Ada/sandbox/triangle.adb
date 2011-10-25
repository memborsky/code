with Ada.TEXT_IO, Ada.INTEGER_TEXT_IO;
use Ada.TEXT_IO, Ada.INTEGER_TEXT_IO;
Procedure triangle is

    PROCEDURE Get_Data (sideA, sideB, sideC : in out integer) is

    BEGIN

		Ada.TEXT_IO.put_line("    Input    ");
		Ada.TEXT_IO.put_line("-------------");

		Ada.TEXT_IO.put ("Please input side A - ");
		Ada.INTEGER_TEXT_IO.get(sideA);

		Ada.TEXT_IO.put ("Please input side B - ");
		Ada.INTEGER_TEXT_IO.get(sideB);

		Ada.TEXT_IO.put ("Please input side C - ");
		Ada.INTEGER_TEXT_IO.get(sideC);
		

    END Get_Data;

--------------------------------------------------------------------------------------

    FUNCTION Is_Triangle (sideA, sideB, sideC : integer) return boolean is

	answer : boolean;

    BEGIN

	    if (sideA + sideB) >= sideC then
			answer := true;
	    else
			answer := false;
	    end if;

		return answer;

    END Is_Triangle;

-------------------------------------------------------------------------------------

	PROCEDURE Put_Data (sideA, sideB, sideC : integer; is_tri : boolean) is

	BEGIN

		Ada.TEXT_IO.new_line(3);
		Ada.TEXT_IO.put_line("    Output    ");
		Ada.TEXT_IO.put_line("--------------");

		Ada.INTEGER_TEXT_IO.put(sideA, 0);
		Ada.TEXT_IO.put(", ");
		Ada.INTEGER_TEXT_IO.put(sideB, 0);
		Ada.TEXT_IO.put(", ");
		Ada.INTEGER_TEXT_IO.put(sideC, 0);

			if is_tri then
				Ada.TEXT_IO.put(" = A ");
				-- Put the rest of the program, like the type of triangle it is.
				-- For now we just put that it is a triangle.
				Ada.TEXT_IO.put("triangle.");
			else
				Ada.TEXT_IO.put(" /= A triangle.");
			end if;

	END Put_Data;

-------------------------------------------------------------------------------------

	sideA, sideB, sideC : integer := 0;
	is_tri : boolean := false;

BEGIN

	-- Gather our data.
	Get_Data(sideA, sideB, sideC);
	
	-- Checking to make sure our data makes a triangle or not.
	is_tri := Is_Triangle(sideA, sideB, sideC);
	
	-- Print our results.
	Put_Data(sideA, sideB, sideC, is_tri);

END triangle;
