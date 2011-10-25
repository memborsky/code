   WITH
      
      Ada.TEXT_IO,
      Ada.INTEGER_TEXT_IO,
      Ada.Strings.Fixed;

    PROCEDURE shop IS
   
      min : CONSTANT INTEGER := 1;
      max : CONSTANT INTEGER := 100;
   
   ------
   
      TYPE list IS RECORD
            data : string ( 1 .. 1000 );
            length : INTEGER;
            number : INTEGER;
         END RECORD;
   ---
   
      TYPE product_name IS ARRAY(min .. max) OF list;
      TYPE product_number IS ARRAY(min .. max) OF list;
   
      p_name : product_name;
      p_number : product_number;
   
   ------
   
      TYPE howman IS RECORD
            product_name : INTEGER;
            product_number : INTEGER;
         END RECORD;
   
      howmany : howman;
   
   ------
   
   --------------------------------------------------------------------
   
       FUNCTION full ( how_many : IN howman ) RETURN boolean IS
     
         answer : BOOLEAN := FALSE;
     
      BEGIN
     
         IF how_many.product_number >= max THEN
         
            answer := TRUE;
         
         ELSE
         
            answer := FALSE;
         
         END IF;
     
         RETURN answer;
     
      END full;
   
   --------------------------------------------------------------------
   
       FUNCTION check_value ( lst : IN list; how_many : IN howman ) RETURN BOOLEAN IS
     
         check : INTEGER;
         index : INTEGER;
         whole_value : INTEGER;
         answer : BOOLEAN := TRUE;
     
      BEGIN
     
     
     
         WHILE index <= check - 1 LOOP
         
            whole_value := integer'value ( item );
         
         END LOOP;
         
         RETURN answer;
     
      END check_value;   
   --------------------------------------------------------------------
   
       PROCEDURE get_data ( lst : IN list; how_many : IN howman ) IS
     
         index : INTEGER;
     
      BEGIN
         index := min;
         WHILE NOT end_of_file LOOP
            Ada.TEXT_IO.get_line(product_name(index).data, product_name(index).length);
            Ada.TEXT_IO.get_line(product_number(index).data, product_name(index).length);
            how_many.product_name := how_many.product_name + 1;
            how_many.product_number := how_many.product_number + 1;
            index := index + 1;
         END LOOP;
     
      END get_data;
     
   --------------------------------------------------------------------
   
       PROCEDURE create ( how_many : IN OUT howman ) IS
     
      BEGIN
         
         how_many.product_name := 0;
         how_many.product_number := 0;
     
      END create;
   
   --------------------------------------------------------------------
   
       PROCEDURE append (lst : IN OUT list; how_many : IN OUT howman ) IS
     
      BEGIN
     
         IF NOT full(how_many) THEN
         
            lst.howmany := lst.howmany + 1;
            lst.data(lst.howmany) := item;
         
         ELSE
         
            Ada.TEXT_IO.put("***ERROR: Due to the list being full, That item can not be appended to the list.***"); 
--error raised on list being full
         
         END IF;
     
      END append;
   
   --------------------------------------------------------------------
   
       PROCEDURE sort (lst: IN OUT list; item : IN datum) IS
     
      BEGIN
     
         null;
     
      END sort;
   
   --------------------------------------------------------------------
   
       PROCEDURE insertion_sort ( lst : IN OUT list ) IS
     
         sorted : INTEGER;
     
      BEGIN
     
         sorted := 0;
         WHILE sorted < lst.howmany LOOP
         
            insert ( lst, lst.data(sorted + 1), sorted );
         
         END LOOP;
     
      END insertion_sort;
   
   --------------------------------------------------------------------
   
   BEGIN
   
      null; --main program
   
   END shop;
