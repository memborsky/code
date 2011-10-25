----------------------------------------------------------------
--| Assignment 7, Bowling
--| Matt Emborsky
--| CS1305AA
--| Due Date: Friday 4/16/2004
----------------------------------------------------------------
----------------------------------------------------------------
--| This program takes bowling scores and scores and formats them
--| 
--| Author: Matt Emborsky
--|                   April 2004
----------------------------------------------------------------
   WITH
   
   Ada.TEXT_IO,
   Ada.INTEGER_TEXT_IO;
   
   USE
   
   Ada.TEXT_IO,
   Ada.INTEGER_TEXT_IO;

    PROCEDURE bowling2 IS
   
      TYPE balls IS ARRAY(1 .. 22) OF character;
      ball : balls;
      framenum : INTEGER;
      dummy : character;
      ballnum : INTEGER;
      total : INTEGER;
      exity : BOOLEAN := FALSE;
      howmany : INTEGER;
   
       PROCEDURE frame_1_9_out IS
      
      BEGIN
      
         ballnum := 1;
         WHILE ballnum <= howmany LOOP
         
            Ada.TEXT_IO.put(ball(ballnum));
            Ada.TEXT_IO.put(" ");
         
            IF ball(ballnum) = 'X' OR ball(ballnum) = 'x' then
               
               total := total + 10;
               
               IF ball(ballnum + 1) = 'X' OR ball(ballnum + 1) = 'x' then
                  
                  total := total + 10;
                  
                  IF ball(ballnum + 2) = 'X' OR ball(ballnum + 2) = 'x' then
                     
                     total := total + 10;
                  
                  ELSIF ball(ballnum + 2) /= 'X' OR ball(ballnum + 2) = 'x' then
                     
                     total := total + (character'pos (ball(ballnum + 2)) - character'pos ('0'));
                  
                  END IF;
                  
                  Ada.INTEGER_TEXT_IO.put(total, width => 1);
                  Ada.TEXT_IO.put(" | ");
               
               ELSIF ball(ballnum + 2) = '/' then
                  
                  total := total + (10);
               
               ELSE
                  
                  total := total + (character'pos (ball(ballnum + 1)) - character'pos ('0'));
                  total := total + (character'pos (ball(ballnum + 2)) - character'pos ('0'));
                  Ada.INTEGER_TEXT_IO.put(total, width => 1);
                  Ada.TEXT_IO.put(" | ");
               
               END IF;
            
            ELSIF ball(ballnum + 1) = '/' then
               
               total := total + 10;
               
               IF ball(ballnum) = 'X' OR ball(ballnum) = 'x' then
                  total := total + 10;
                  Ada.INTEGER_TEXT_IO.put(total, width => 1);
                  Ada.TEXT_IO.put(" | ");
               
               ELSIF ball(ballnum + 2) /= 'X' OR ball(ballnum + 2) = 'x' THEN
                  
                  Ada.TEXT_IO.put(ball(ballnum + 2));
                  Ada.TEXT_IO.put(" ");
                  total := total + (character'pos (ball(ballnum + 2)) - character'pos ('0'));
                  Ada.INTEGER_TEXT_IO.put(total, width => 1);
                  Ada.TEXT_IO.put(" | ");
               
               END IF;
               
            ELSE
               
               total := total + (character'pos (ball(ballnum)) - character'pos ('0'));
               Ada.TEXT_IO.put(ball(ballnum + 1));
               Ada.TEXT_IO.put(" ");
               total := total + (character'pos (ball(ballnum + 1)) - character'pos ('0'));
               Ada.INTEGER_TEXT_IO.put(total, width => 1);
               Ada.TEXT_IO.put(" | ");
            
            END IF;
         
            ballnum := ballnum + 1;
         
         END LOOP;
      
      END frame_1_9_out;
   
   
       PROCEDURE frame_10_out IS
      
      BEGIN
      
         Ada.TEXT_IO.put(ball(ballnum));
         Ada.TEXT_IO.put(" ");
         Ada.TEXT_IO.put(ball(ballnum + 1));
         Ada.TEXT_IO.put(" ");
      
         
         IF ball(ballnum) ='X' OR ball(ballnum) = 'x' THEN
            
            total := total + 10;
            
            IF ball(ballnum + 1) = 'X' OR ball(ballnum + 1) = 'x' then
               
               Ada.TEXT_IO.put(ball(ballnum + 2));
               Ada.TEXT_IO.put(" ");  
               total := total + 10;
               
               IF ball(ballnum + 2) = 'X' OR ball(ballnum + 2) = 'x' THEN
                
                  total := total + 10;
               
               ELSE 
                  
                  total := total + (character'pos (ball(ballnum + 2)) - character'pos ('0'));
               
               END IF;
               
               Ada.INTEGER_TEXT_IO.put(total, width => 1);
               Ada.TEXT_IO.put(" | ");
               Ada.INTEGER_TEXT_IO.put(total, width => 1);
               
            ELSE 
               
               IF ball(ballnum + 2) = '/' then
               
                  Ada.TEXT_IO.put(ball(ballnum + 2));
                  Ada.TEXT_IO.put(" ");   
                  total := total + 10;
               
               ELSE
                  
                  total := total + ((character'pos (ball(ballnum + 1)) - character'pos ('0')) + (character'pos (ball(ballnum + 2)) - character'pos ('0')));
               
               END IF;
               
               Ada.TEXT_IO.put(" | ");
               Ada.INTEGER_TEXT_IO.put(total, width => 1);
               
            END IF;
            
         ELSIF ball(ballnum + 1) = '/' then
         
            Ada.TEXT_IO.put(" ");
            Ada.TEXT_IO.put(ball(ballnum + 2));
            total := total + 10;
            
            IF ball(ballnum + 2) = 'X' OR ball(ballnum + 2) = 'x' THEN
               
               total := total + 10;
               Ada.TEXT_IO.put(" | ");
               Ada.INTEGER_TEXT_IO.put(total, width => 1);
               
            ELSE
               
               total := total + (character'pos (ball(ballnum + 2)) - character'pos ('0'));
               Ada.TEXT_IO.put(" | ");
               Ada.INTEGER_TEXT_IO.put(total, width => 1);
            
            END IF;
            
         ELSE
         
            Ada.TEXT_IO.put(ball(ballnum + 1));
            Ada.TEXT_IO.put(" ");
            total := total + (character'pos (ball(ballnum)) - character'pos ('0'));
            total := total + (character'pos (ball(ballnum + 1)) - character'pos ('0'));
            Ada.INTEGER_TEXT_IO.put(total, width => 1);
            Ada.TEXT_IO.put(" | ");
            Ada.INTEGER_TEXT_IO.put(total, width => 1);
            
         END IF;
      
      END frame_10_out;
   
   
       PROCEDURE get_ball IS
      
         index : INTEGER;
      
      BEGIN
      
         index := 1;
         
         WHILE NOT Ada.TEXT_IO.end_of_file LOOP
         
            Ada.TEXT_IO.get(ball(index));
            
            IF ball(1) = 'G' OR ball(1) = 'g' THEN
            
               exity := TRUE;
               EXIT;
            
            END IF;
         
         
            -- IF exity = FALSE THEN
            
            IF ball(index) = 'X' OR ball(index) = 'x' THEN
               
               IF framenum = 10 THEN
                  
                  -- frame_1_9_out;
                  Ada.TEXT_IO.get(dummy);
                  Ada.TEXT_IO.get(ball(index + 1));
                  Ada.TEXT_IO.get(dummy);
                  Ada.TEXT_IO.get(ball(index + 2));
                  howmany := index + 2;
                  -- frame_10_out;
                  EXIT;
                  
               END IF;
                  
               framenum := framenum + 1;
               Ada.TEXT_IO.get(dummy);
               
            ELSE
               
               IF framenum = 10 THEN
                  
                  -- frame_1_9_out;
                  Ada.TEXT_IO.get(dummy);
                  Ada.TEXT_IO.get(ball(index + 1));
                  howmany := index + 1;
                  
                  IF ball(index + 1) = '/' THEN
                     
                     Ada.TEXT_IO.get(dummy);
                     Ada.TEXT_IO.get(ball(index + 2));
                     howmany:= index + 2;
                     
                  END IF;
                  
                  -- frame_10_out;
                  EXIT;
                  
               END IF;
               
            END IF;
            
            -- END IF;
         
            howmany := index;
            index := index + 1;
            framenum := framenum + 1;
         
         END LOOP;
      
      END get_ball;
   
   BEGIN
   
      -- LOOP
      
         framenum := 0;
         dummy := ' ';
         ballnum := 0;
         total := 0;
         exity := FALSE;
      
         get_ball;
         Ada.TEXT_IO.new_line;
         frame_1_9_out;
         frame_10_out;
      
      -- END LOOP;
   
   END bowling2;