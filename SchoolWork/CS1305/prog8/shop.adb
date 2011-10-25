--------------------------------------------------------------------
--| program 8
--| Assignment takes a file opens and sorts the data and search 
--|     the data for a certain product number from input
--|
--|Written by : Matt Emborsky, Indiana Institute of Technology
--------------------------------------------------------------------

   WITH
      
      Ada.TEXT_IO,
      Ada.INTEGER_TEXT_IO;

    PROCEDURE shop IS
   
      min : CONSTANT INTEGER := 1;
      max : CONSTANT INTEGER := 100;
   
   ------
   
      TYPE lists IS RECORD
            data : string ( 1 .. 1000 );
            data_length : INTEGER;
            number : INTEGER;
            whole_number : INTEGER;
         END RECORD;
   
      TYPE product IS ARRAY(0 .. max) OF lists;
   
      list : product;
      
   ------
   
      TYPE chara IS ARRAY(1 .. 6) OF character;
      char : chara;
   
   ------
   
      check_digit : INTEGER;
      uncheck_digit : INTEGER;
      blank : BOOLEAN;
      temlist : string(1 .. 50);
      temlist_length : INTEGER;
      howmany : INTEGER;
   
   ------
   
      out_data : Ada.TEXT_IO.File_Type;
      in_data : Ada.TEXT_IO.File_Type;
      
   --------------------------------------------------------------------
   
       PROCEDURE put_data( list_1 : IN OUT product; list_2 : IN OUT 
product; how_many : IN OUT INTEGER ) IS
      
         index : INTEGER;
      
      BEGIN
      
         index := min;
      
         WHILE index <= how_many LOOP
         
            Ada.TEXT_IO.put_line(out_data, list_1(index).data(1 .. 
list_1(index).data_length));
            Ada.INTEGER_TEXT_IO.put(out_data, list_2(index).whole_number, 
1);
            Ada.TEXT_IO.new_line(out_data);
         
            index := index + 1;
         
         END LOOP;
      
      END put_data;
   
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
   
       PROCEDURE search ( list_1 : IN OUT product; how_many : IN OUT 
INTEGER; part : IN INTEGER ) IS
      
      BEGIN
      
         FOR i IN 1 .. how_many LOOP
         
            IF part = list_1(i).number THEN
            
            Ada.TEXT_IO.put("""");
               Ada.INTEGER_TEXT_IO.put(list_1(i).number, 1);
               Ada.TEXT_IO.put(""" product number with name """ & 
list_1(i).data(1 .. list_1(i).data_length) & """ was found in place """);
               Ada.INTEGER_TEXT_IO.put(i, 1);
               Ada.TEXT_IO.put("""");
               Ada.TEXT_IO.new_line;
            
            END IF;
         
         END LOOP;
      
      END search;
   --------------------------------------------------------------------
   
       PROCEDURE get_data ( list_1 : IN OUT product; how_many : IN OUT 
INTEGER ) IS
      
         index : INTEGER;
      
      BEGIN
      
      ------
      
         Ada.TEXT_IO.open(in_data, Ada.Text_IO.In_File, "input8.txt");
         Ada.TEXT_IO.create(out_data, Ada.Text_IO.Out_File, 
"output8.txt");
      
      ------
      
         index := min;
      	
         LOOP
         
            check_digit := 0;
            uncheck_digit := 0;
         
         
-------------------------------------------------------------------------------------
         
            IF Ada.TEXT_IO.end_of_line(in_data) THEN
            
               EXIT;
            
            END IF;
           
            Ada.TEXT_IO.get_line(in_data, temlist, temlist_length);
         
            FOR got IN 1..5 LOOP
            
               Ada.TEXT_IO.get(in_data, char(got));
               uncheck_digit := (character'pos(char(got)) - 48) + 
uncheck_digit;
            
            END LOOP;
         
            Ada.TEXT_IO.get(in_data, char(6));
         
            check_digit := character'pos(char(6)) - 48;
         
            uncheck_digit := uncheck_digit REM 10;
            
         
-------------------------------------------------------------------------------------
         
            IF check_digit = uncheck_digit THEN
            
               blank := TRUE;
               
            ELSE
            
               blank := FALSE;
            
            END IF;
         
         
-------------------------------------------------------------------------------------
         
            IF blank = TRUE THEN
            
               list_1(index).data(1..temlist_length) := 
temlist(1..temlist_length);
               list_1(index).data_length := temlist_length;
            
               FOR gotten IN 1 .. 5 LOOP
               
                  list_1(index).number := (list_1(index).number * 10) + 
(character'pos(char(gotten)) - 48);
               
               END LOOP;
            
               FOR git IN 1 .. 6 LOOP
               
                  list_1(index).whole_number := 
(list_1(index).whole_number * 10) + (character'pos(char(git)) - 48);
               
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
   
       PROCEDURE insert ( list_1 : IN OUT product; item_1 : IN INTEGER; 
item_2 : IN string; how_many : IN OUT INTEGER ) IS
      
         index : INTEGER;
         temp_record : lists;
      
      BEGIN
         
         index := how_many;
         temp_record := list_1(index + 1);
      	
         WHILE item_1 < list_1(index).number AND THEN full(howmany) LOOP
            
            list_1(index + 1) := list_1(index);
            index := index - 1;
            
         END LOOP;
         
         list_1(index + 1) := temp_record;
      	
         how_many := how_many + 1;
      
      END insert; 
   
   --------------------------------------------------------------------
   
       PROCEDURE insertion_sort ( list_1 : IN OUT product; how_many : IN 
OUT INTEGER ) IS
      
         sorted : INTEGER;
      
      BEGIN
      
         sorted := 1;
         WHILE sorted < howmany LOOP
         
            insert ( list_1, list_1(sorted + 1).number, list_1(sorted + 
1).data, sorted );
         
         END LOOP;
      
      END insertion_sort;
   
   --------------------------------------------------------------------
   
      choice : BOOLEAN;
      choices : INTEGER;
      part : INTEGER;
   
   BEGIN
   
      choice := TRUE;
      list(0).number := -2147483647;
      get_data(list, howmany);
      insertion_sort(list, howmany);
      put_data(list, list, howmany);
   
      WHILE choice = TRUE LOOP
      
         Ada.TEXT_IO.put("Input 1 to Search and 2 to exit - ");
         Ada.INTEGER_TEXT_IO.get(choices);
      
         IF choices = 1 THEN
         
            Ada.TEXT_IO.put("Input part number to search for - ");
            Ada.INTEGER_TEXT_IO.get(part);
            search (list, howmany, part);
            choice := TRUE;
         
         ELSE
         
            choice := FALSE;
         
         END IF;
      
      END LOOP;
   
   END shop;
