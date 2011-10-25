   WITH
      
      Ada.TEXT_IO,
      Ada.INTEGER_TEXT_IO;

    PROCEDURE shop IS
   
      min : CONSTANT INTEGER := 1;
      max : CONSTANT INTEGER := 100;
   
   ------
   
      TYPE list IS RECORD
            data : string ( 1 .. 1000 );
            data_length : INTEGER;
            number : INTEGER;
            whole_number : INTEGER;
         END RECORD;
   
      TYPE product IS ARRAY(0 .. max) OF list;
   
      p_name : product;
      p_number : product;
   
   ------
   
      TYPE chara IS ARRAY(1 .. 6) OF character;
      char : chara;
   
   ------
   
      -- TYPE howman IS RECORD
            -- product_name : INTEGER;
            -- product_number : INTEGER;
         -- END RECORD;
   
      -- howmany : howman;
   
   ------
   
      check_digit : INTEGER;
      uncheck_digit : INTEGER;
      blank : BOOLEAN;
      temp_name : string(1 .. 50);
      temp_name_length : INTEGER;
      -- sorted : INTEGER;
      howmany : INTEGER;
   
   ------
   
      out_data : Ada.TEXT_IO.File_Type;
      in_data : Ada.TEXT_IO.File_Type;
      
   --------------------------------------------------------------------
   
       PROCEDURE put_data( list_1 : IN OUT product; list_2 : IN OUT product; how_many : IN OUT INTEGER ) IS
      
         index : INTEGER;
      
      BEGIN
      
         index := min;
      
         WHILE index <= how_many LOOP
         
            Ada.TEXT_IO.put_line(out_data, list_1(index).data(1 .. list_1(index).data_length));
            Ada.INTEGER_TEXT_IO.put(out_data, list_2(index).number);
            Ada.TEXT_IO.new_line(out_data);
         
            index := index + 1;
         
         END LOOP;
      
      END put_data;
   
   -------------------------------------------------------------------
   
   
   
   -------------------------------------------------------------------
   
       FUNCTION full ( how_many : IN INTEGER ) RETURN boolean IS
      
         answer : BOOLEAN := FALSE;
      
      BEGIN
      
         IF how_many >= max THEN
         
            answer := FALSE;
         
         ELSE
         
            answer := TRUE;
         
         END IF;
      
         RETURN answer;
      
      END full;
   
   --------------------------------------------------------------------
   
       PROCEDURE get_data ( list_1 : IN OUT product; list_2 : IN OUT product; how_many : IN OUT INTEGER ) IS
      
         index : INTEGER;
      
      BEGIN
      
      ------
      
         Ada.TEXT_IO.open(in_data, Ada.Text_IO.In_File, "input8.txt");
         Ada.TEXT_IO.create(out_data, Ada.Text_IO.Out_File, "output8.txt");
      
      ------
      
      
         index := min;
      	
         LOOP
         
            check_digit := 0;
            uncheck_digit := 0;
         
            -- IF (index >= 2) THEN
            
               -- Ada.TEXT_IO.new_line(2);
               -- Ada.TEXT_IO.put(temp_name(1 .. temp_name_length));
               
            --    FOR git IN 1 .. 5 LOOP
             	
            -- 	Ada.TEXT_IO.put(char(git));
            	
            -- 	END LOOP;
            	
            --    Ada.TEXT_IO.put(" ");
               -- Ada.INTEGER_TEXT_IO.put(uncheck_digit, 1);
               -- Ada.TEXT_IO.put(" ");
               -- Ada.INTEGER_TEXT_IO.put(check_digit, 1);
            
            -- END IF;
         
         -------------------------------------------------------------------------------------
         
            IF Ada.TEXT_IO.end_of_line(in_data) THEN
            
               EXIT;
            
            END IF;
           
            Ada.TEXT_IO.get_line(in_data, temp_name, temp_name_length);
            Ada.TEXT_IO.put(temp_name(1 .. temp_name_length));
            Ada.TEXT_IO.new_line;
            
            FOR got IN 1..5 LOOP
            
               Ada.TEXT_IO.get(in_data, char(got));
               Ada.TEXT_IO.put(char(got));
               uncheck_digit := (character'pos(char(got)) - 48) + uncheck_digit;
            
            END LOOP;
         
            Ada.TEXT_IO.get(in_data, char(6));
            Ada.TEXT_IO.put(char(6));
            
            check_digit := character'pos(char(6)) - 48;
         
            uncheck_digit := uncheck_digit REM 10;
            Ada.TEXT_IO.new_line;
         	
         -------------------------------------------------------------------------------------
         
            IF check_digit = uncheck_digit THEN
            
               blank := TRUE;
               
            ELSE
            
               blank := FALSE;
            
            END IF;
         
         -------------------------------------------------------------------------------------
         
            IF blank = TRUE THEN
            
               list_1(index).data(1..temp_name_length) := temp_name(1..temp_name_length);
               list_1(index).data_length := temp_name_length;
            
               FOR gotten IN 1 .. 5 LOOP
               
                  list_2(index).number := (list_2(index).number * 10) + (character'pos(char(gotten)) - 48);
               
               END LOOP;
            
               FOR git IN 1 .. 6 LOOP
               
                  list_2(index).whole_number := (list_2(index).whole_number * 10) + (character'pos(char(git)) - 48);
               
               END LOOP;
               
               how_many := how_many + 1;
               index := index + 1;
            
            END IF;
         
            EXIT WHEN Ada.TEXT_IO.end_of_file(in_data);
            Ada.TEXT_IO.skip_line(in_data);
         
         END LOOP;
      
      END get_data;
     
   --------------------------------------------------------------------
   
       PROCEDURE create ( how_many : IN OUT INTEGER ) IS
      
      BEGIN
         
         how_many := 0;
      
      END create;
   
   --------------------------------------------------------------------
   
       PROCEDURE insert ( list_1 : IN OUT product; list_2 : IN OUT product; item_1 : IN string; item_2 : IN INTEGER; how_many : IN OUT INTEGER ) IS
      
         index : INTEGER;
         temp_length_1_1 : INTEGER;
         temp_length_1_2 : INTEGER;
         temp_length_1_3 : INTEGER;
         temp_data_1_1 : string(1 .. 1000);
         temp_data_1_2 : string(1 .. 1000);
      
      BEGIN
         
         index := how_many;
            
         WHILE item_2 < list_2(index).number AND THEN full(howmany) LOOP
            
            temp_length_1_1 := p_name(index).data_length;
            temp_length_1_2 := p_name(index + 1).data_length;
            temp_length_1_3 := p_name(index - 1).data_length;
            temp_data_1_1 := list_1(index).data(1 .. temp_length_1_1);
            temp_data_1_2 := list_1(index + 1).data(1 .. temp_length_1_2);
            list_1(index + 1).data := list_1(index).data;
            list_2(index + 1).number := list_2(index).number;
            index := index - 1;
            
         END LOOP;
         
         list_1(index + 1).data := item_1;
         list_2(index + 1).number := item_2;
         
         how_many := how_many + 1;
      
      END insert; 
   
   --------------------------------------------------------------------
   
       PROCEDURE insertion_sort ( list_1 : IN OUT product; list_2 : IN OUT product; how_many : IN OUT INTEGER ) IS
      
         sorted : INTEGER;
      
      BEGIN
      
         sorted := 0;
         WHILE sorted < howmany LOOP
         
            insert ( list_1, list_2, list_1(sorted + 1).data, list_2(sorted + 1).number, sorted );
         
         END LOOP;
      
      END insertion_sort;
   
   --------------------------------------------------------------------
   
   BEGIN
   
      p_number(0).number := -2147483647;
      get_data(p_name, p_number, howmany);
      insertion_sort(p_name, p_number, howmany);
      put_data(p_name, p_number, howmany);
   
   END shop;