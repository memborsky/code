   WITH -- this section has all packages that are used in this program
   Ada.TEXT_IO,
   Ada.INTEGER_TEXT_IO,
   Ada.FLOAT_TEXT_IO,
   Ada.STRINGS,
   Ada.STRINGS.fixed,
   Ada.STRINGS.maps;

   USE -- this section says that I'm going to be using these packages in the program
   -- the advantage of the USE is that I don't have to type out Ada.<package>.<call>
   -- in the program as the compiler knows what package and call I'm using.
   -- Makes for easier coding and less typing.
   Ada.TEXT_IO,
   Ada.INTEGER_TEXT_IO,
   Ada.FLOAT_TEXT_IO,
   Ada.STRINGS,
   Ada.STRINGS.fixed,
   Ada.STRINGS.maps;

    PROCEDURE convert IS -- start of program
   
      number : string(1..30); -- input value
      number_length : NATURAL; -- length of input value
      answer_float : FLOAT; -- answer if float
      answer_integer : INTEGER; -- answer if integer
      zero_or_not : BOOLEAN := FALSE; -- checked to see if the number entered is zero or not.
      
       PROCEDURE get_data IS -- declares the procedure get_data for the program convert
      -- This procedure just gets data from the user and stores it into the string variable
      -- number with the length of number being stored into number_length.
      
      BEGIN -- begins get_data procedure
      
         put ("Please input a number [enter 0 to exit] - "); -- print line asking for input of data
         get_line(number, number_length); -- stores the line inputed into number with the length
         -- of number getting stored into number_length
      
         IF index (number (number'first .. number_length), ".") > 0 THEN
         
            zero_or_not := FALSE;
         
         ELSE
         
            IF integer'value (number(number'first .. number_length)) = 0 THEN
            
               zero_or_not := TRUE;
            
            END IF;
         
         END IF;
      
      
      
      END get_data; -- ends procedure to get the data
      
       
       PROCEDURE convert_print_data IS -- declares the procedure convert_data for the program convert.
       -- This procedure takes the data that was inputed in the previous procedure and sends it to a 
       -- function call inside the package Ada.STRINGS.fixed to find out if data is valid or not
       -- and in doing so prints out the results. If found to be an integer it will
       -- print out 'Integer Numerical value found in - "$number"
      
      BEGIN -- begin convert_data
       
         IF index (number (number'first .. number_length), ".") > 0 THEN
         -- This if statement sends to the function index in the package Ada.STRINGS.fixed, which
         -- when sending the string number into checks the second parameter, in this case is a '.'
         -- The function will take a string and split it down to character by character and check to
         -- see if the "." is in the string.  Once it finds one it will return a natural of 1 and the
         -- becomes true and you continue to the next lines, else if the return is 0 the statement is
         -- false and you continue to the 
         		
            answer_float := float'value (number (number'first .. number_length));
            -- this statement turns the value of number into a float numerical value
         	
            put ("'");
            put (number(number'first .. number_length));
            put ("' has a Float value of '");
            put (answer_float, exp => 0, aft => 0);
            put ("'");
            -- this line puts the original value plus the Float value of number onto the screen
            new_line;
         	
         ELSE
               
            answer_integer := integer'value (number (number'first .. number_length));
            -- this statement turns the value of number into an integer numerical value
         	   
            put ("'");
            put (number(number'first .. number_length));
            put ("' has an Integer value of '");
            put (answer_integer, 1);
            put ("'");
         	-- this line puts the original value plus the Integer value of number onto the screen
            new_line;
         	
         END IF;
         
      	
          EXCEPTION
         
            -- This is the area where all exceptions are handled.
         	-- Meaning if the number had a value of 1234abcd stored in it then the following statements
         	-- would be executed. As well as the value or 1234.1234.1234 would rase the EXCEPTION error
         	-- and the following lines would be executed.  
         	
            WHEN OTHERS =>	
            	
               put_line ("ERROR: Invalid input string - '" & number(number'first .. number_length) & "'");
      		-- this line prints out that there was an error in the input data and shows the line of data
      		
      END convert_print_data; -- this ends the procedure convert_print_data
      
   	
   BEGIN -- begins the main program convert
   
      LOOP -- start of data loop
      
         get_data; -- calls the procedure to get data
      
         EXIT WHEN zero_or_not = TRUE;
         -- This line makes sure that a value less than 0 was not entered.   
      		
         convert_print_data; -- calls the procedure to convert and print the output of the data
      
      END LOOP; -- end of data loop
   
   END convert; -- ends the main program convert data
