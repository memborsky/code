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
      framenum : INTEGER := 1;
      test : INTEGER := 1;
      dummy : character := ' ';
      ballnum : INTEGER := 1;
      total : INTEGER := 0;
      count : INTEGER := 21;
      nonstrikeball : BOOLEAN := FALSE;
      exity : BOOLEAN := FALSE;
      leave : INTEGER := 1;

       PROCEDURE get_ball IS
      
      BEGIN
      
         framenum := 1;
         LOOP
         
            Ada.TEXT_IO.get(ball(ballnum));
            
         
            IF ball(1) = 'G' OR ball(1) = 'g' THEN
            
               exity := TRUE;
               EXIT;
            
            END IF;
         
         
            IF exity = FALSE THEN
            
               IF ball(ballnum) = 'X' OR ball(ballnum) = 'x' THEN  -- this block will happen if and only if a strike has been found in the ball(ballnum) array character
               
--                  IF count <= 0 OR framenum >= 10 THEN  -- for debuging purposes (START)
--                     LOOP
--                        put(count);
--                        put(ball(test));
--                        EXIT WHEN test >= 21;
--                        test := test + 1;
--                     END LOOP;
--                  END IF;                               -- for debuging purposes (END)
               
                  count := count - 2;             -- decress count expected to exit by 2 becasue of a strike
                  ballnum := ballnum + 1;         -- increase ballnum to the next numbered array
               
                  IF framenum = 10 THEN           -- exception
                
                     count := count - 4;          -- testing :: sorta passed
--                     put("FRAME 10");             -- debuging purposes
                  
                  END IF;
               
                  framenum := framenum + 1;       -- increase framenum, used to do an exception on frame 10
                  Ada.TEXT_IO.get(dummy);         -- get the space after the ball
               
                  EXIT WHEN count <= 0 OR framenum >= 11;  -- exit
               
               ELSE                               -- happens if the ball(ballnum)
               
--                  IF count <= 0 OR framenum >= 10 THEN
                  
--                     LOOP
                     
--                        put(count);
--                        put(ball(test));
--                        EXIT WHEN test >= 21;
--                        test := test + 1;
                     
--                     END LOOP;
                  
--                  END IF;
               
                  EXIT WHEN count <= 0 OR framenum >= 11;
               
                  Ada.TEXT_IO.get(dummy);
                  count := count - 1;
                  ballnum := ballnum + 1;
               
                  IF framenum = 10 THEN
                  
                  
                     count := count - 2;
--                     put("FRAME 10");
                  
                  END IF;
               
                  IF nonstrikeball = FALSE THEN
                  
                     nonstrikeball := TRUE;
                  
                  ELSIF nonstrikeball = TRUE THEN
                  
                     framenum := framenum + 1;
                     nonstrikeball := FALSE;
                  
                  END IF;
               
               END IF;
            
            END IF;
         
         END LOOP;
      
      END get_ball;
   
   
       PROCEDURE frame_1_9_out IS
      
      BEGIN
      
         ballnum := 1;
         FOR index IN 1 .. 9 LOOP
         
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
         
            IF ball(ballnum) = 'X' OR ball(ballnum) = 'x' THEN
               
               ballnum := ballnum + 1;
            
            ELSE
               
               ballnum := ballnum + 2;
            
            END IF;
         
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
   
   
   BEGIN

     LOOP

	leave := 1;
	LOOP
	  ball(leave) := ' ';
	  framenum := 1;
	  test := 1;
	  dummy := ' ';
	  ballnum := 1;
	  total := 0;
	  count := 21;
	  nonstrikeball := FALSE;
	  exity := FALSE;
	  leave := leave + 1;
	  EXIT WHEN leave = 22;
	END LOOP;

      
         get_ball;
         Ada.TEXT_IO.new_line;
      
         IF exity = FALSE THEN
         
            frame_1_9_out;
            frame_10_out;
         
         ELSE
         
            Ada.TEXT_IO.put("Please play again");
            EXIT;
         
         END IF;
      
      END LOOP;
   
   END bowling2;
