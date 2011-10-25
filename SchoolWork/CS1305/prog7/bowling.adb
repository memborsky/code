----------------------------------------------------------------
--| Assignment 7, Bowling
--| Kris DelBarba

--| CS1305AA

--| Due Date: Friday 4/16/2004

----------------------------------------------------------------
----------------------------------------------------------------
--| This program takes bowling scores and scores and formats them
--| 
--| Author: Kris DelBarba Indiana Institute of Technology
--|                   April 2004
----------------------------------------------------------------

   WITH Ada.Text_IO;
   WITH Ada.Integer_Text_IO;

    Procedure Bowling IS
      output : Ada.Text_IO.File_type;
      fendf : Integer := 0;
   
       procedure Game IS
      
         ch : Character;
         total : Integer := 0;
         chnumber : integer;
         dummy : character;
         strike : integer := 0;
         spare : integer := 0;
         lendl : boolean;
         ch1a,
         ch1b,
         ch2a,
         ch2b,
         ch3a,
         ch3b,
         ch4a,
         ch4b,
         ch5a,
         ch5b,
         ch6a,
         ch6b,
         ch7a,
         ch7b,
         ch8a,
         ch8b,
         ch9a,
         ch9b,
         ch10a,
         ch10b,
         ch10c : character;
      
      
      Begin -- games --
         ada.text_IO.look_ahead(ch,lendl);
         if ch = 'G' then
            fendf := 1;
         else
         
          -- input --
         
         -- frame 1 --
            ada.text_IO.get(ch1a);
            if ch1a = 'X' then											-- 1
               null;
            else
               ada.text_IO.get(dummy);
               ada.text_IO.get(ch1b);
            end if;													-- 1
         
         -- frame 2 --
         
            ada.text_IO.get(dummy);
            ada.text_IO.get(ch2a);
            if ch2a = 'X' then											-- 2
               null;
            else
               ada.text_IO.get(dummy);
               ada.text_IO.get(ch2b);
            end if;													-- 2
         
         -- frame 3 --
         
            ada.text_IO.get(dummy);
            ada.text_IO.get(ch3a);
            if ch3a = 'X' then											-- 3
               null;
            else
               ada.text_IO.get(dummy);
               ada.text_IO.get(ch3b);
            end if;													-- 3
         
         -- frame 4 --
         
            ada.text_IO.get(dummy);
            ada.text_IO.get(ch4a);
            if ch4a = 'X' then											-- 4
               null;
            else
               ada.text_IO.get(dummy);
               ada.text_IO.get(ch4b);
            end if;													-- 4
         
         -- frame 5 --
         
            ada.text_IO.get(dummy);
            ada.text_IO.get(ch5a);
            if ch5a = 'X' then											-- 5
               null;
            else
               ada.text_IO.get(dummy);
               ada.text_IO.get(ch5b);
            end if;													-- 5
         
         -- frame 6 --
         
            ada.text_IO.get(dummy);
            ada.text_IO.get(ch6a);
            if ch6a = 'X' then											-- 6
               null;
            else
               ada.text_IO.get(dummy);
               ada.text_IO.get(ch6b);
            end if;													-- 6
         
         -- frame 7 --
         
            ada.text_IO.get(dummy);
            ada.text_IO.get(ch7a);
            if ch7a = 'X' then											-- 7
               null;
            else
               ada.text_IO.get(dummy);
               ada.text_IO.get(ch7b);
            end if;													-- 7
         
         -- frame 8 --
         
            ada.text_IO.get(dummy);
            ada.text_IO.get(ch8a);
            if ch8a = 'X' then											-- 8
               null;
            else
               ada.text_IO.get(dummy);
               ada.text_IO.get(ch8b);
            end if;													-- 8
         
         -- frame 9 --
         
            ada.text_IO.get(dummy);
            ada.text_IO.get(ch9a);
            if ch9a = 'X' then											-- 9
               null;
            else
               ada.text_IO.get(dummy);
               ada.text_IO.get(ch9b);
            end if;													-- 9
         
         -- frame 10 --
         
            ada.text_IO.get(dummy);
            ada.text_IO.get(ch10a);
            ada.text_IO.get(dummy);
            ada.text_IO.get(ch10b);
            if ch10a = 'X' then											-- 10
               ada.text_IO.get(dummy);
               ada.text_IO.get(ch10c);
            end if;													-- 10
            if ch10b = '/' then											-- 11
               ada.text_IO.get(dummy);
               ada.text_IO.get(ch10c);
            end if;													-- 11
         
         -- end frame inputs
         -- check for the end of the games --
            ada.text_IO.skip_line;
         
         
         -- start print out of totals --
         
         -- frame 1 output --
            ada.text_IO.put(output, ch1a);
            ada.text_IO.put(output, " ");
         
            if ch1a = 'X' then											-- 12 open
               total := total + 10;
               if ch2a = 'X' then										-- 13 open
                  total := total + 10;
                  if ch3a = 'X' then 									-- 14 open
                     total := total + 10;
                  elsif ch3a /= 'X' then									-- else 14
                     total := total + (character'pos(ch3a) - 48);
                  end if;											-- 14 close
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               elsif ch2b = '/' then												-- else 13
                  total := total + (10);
               else 
                  total := total + (character'pos(ch2a) - 48);
                  total := total + (character'pos(ch2b) - 48);
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               end if;												-- 13 close
            
            elsif ch1b = '/' then											-- elsif 12
               ada.text_IO.put(output, ch1b);
               ada.text_IO.put(output, " ");
               total := total + 10;
               if ch2a = 'X' then										-- 16 open
                  total := total + 10;
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               elsif ch2a /= 'X' then												-- else 16
                  total := total + (character'pos(ch2a) - 48);
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               end if;												-- 16 close
            
            
            else
               total := total + (character'pos(ch1a) -48);													-- else 12
               ada.text_IO.put(output, ch1b);
               ada.text_IO.put(output, " ");
               total := total + (character'pos(ch1b) - 48);
               ada.integer_text_IO.put(output, total, width => 1);
               ada.text_IO.put(output, " | ");
            
            
            end if;													-- 12 close
         
         -- frame 2 output --
         
         
            ada.text_IO.put(output, ch2a); --
            ada.text_IO.put(output, " ");
         
            if ch2a = 'X' then											-- 12 open
               total := total + 10;
               if ch3a = 'X' then										-- 13 open
                  total := total + 10;
                  if ch4a = 'X' then 									-- 14 open
                     total := total + 10;
                  elsif ch4a /= 'X' then									-- else 14
                     total := total + (character'pos(ch4a) - 48);
                  end if;											-- 14 close
               
               elsif ch3b = '/' then												-- else 13
                  total := total + (10);
               else 
                  total := total + (character'pos(ch3a) - 48);
                  total := total + (character'pos(ch3b) - 48);
               end if;							-- 18 close
               ada.integer_text_IO.put(output, total, width => 1);
               ada.text_IO.put(output, " | ");
            
            elsif ch2b = '/' then
               ada.text_IO.put(output, ch2b);
               ada.text_IO.put(output, " ");
                                         --					-- 17 elsif
               total := total + 10;
               if ch3a = 'X' then                                    --					-- 21 open
                  total := total + 10;
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               elsif ch3a /= 'X' then										-- 21 elsif
                  total := total + (character'pos(ch3a) - 48);         --
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               end if;												-- 21 close
            
            
            else
               total := total + (character'pos(ch2a) -48);													-- 17 else
               ada.text_IO.put(output, ch2b);
               ada.text_IO.put(output, " ");                                        --
               total := total + (character'pos(ch2b) - 48);                  --
               ada.integer_text_IO.put(output, total, width => 1);
               ada.text_IO.put(output, " | ");
            end if;													-- 17 close
         
         
         -- frame 3 output --
         
            ada.text_IO.put(output, ch3a); --
            ada.text_IO.put(output, " ");
         
            if ch3a = 'X' then											-- 12 open
               total := total + 10;
               if ch4a = 'X' then										-- 13 open
                  total := total + 10;
                  if ch5a = 'X' then 									-- 14 open
                     total := total + 10;
                  elsif ch5a /= 'X' then									-- else 14
                     total := total + (character'pos(ch5a) - 48);
                  end if;											-- 14 close
               
               elsif ch4b = '/' then												-- else 13
                  total := total + (10);
               else 
                  total := total + (character'pos(ch4a) - 48);
                  total := total + (character'pos(ch4b) - 48);
                  
               end if;
               ada.integer_text_IO.put(output, total, width => 1);
               ada.text_IO.put(output, " | ");
            	            
            elsif ch3b = '/' then 
               ada.text_IO.put(output, ch3b);
               ada.text_IO.put(output, " ");
                                        --
               total := total + 10;
               if ch4a = 'X' then                                    --
                  total := total + 10;
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               else
                  total := total + (character'pos(ch4a) - 48);         --
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               end if;
            
            
            else
               total := total + (character'pos(ch3a) -48);
               ada.text_IO.put(output, ch3b);
               ada.text_IO.put(output, " ");                                        --
               total := total + (character'pos(ch3b) - 48);                  --
               ada.integer_text_IO.put(output, total, width => 1);
               ada.text_IO.put(output, " | ");
            end if;
         
         -- frame 4 output
         
            ada.text_IO.put(output, ch4a); --
            ada.text_IO.put(output, " ");
         
            if ch4a = 'X' then											-- 12 open
               total := total + 10;
               if ch5a = 'X' then										-- 13 open
                  total := total + 10;
                  if ch6a = 'X' then 									-- 14 open
                     total := total + 10;
                  elsif ch6a /= 'X' then									-- else 14
                     total := total + (character'pos(ch6a) - 48);
                  end if;											-- 14 close
               
               elsif ch5b = '/' then												-- else 13
                  total := total + (10);
               else 
                  total := total + (character'pos(ch5a) - 48);
                  total := total + (character'pos(ch5b) - 48);
               end if;
               ada.integer_text_IO.put(output, total, width => 1);
               ada.text_IO.put(output, " | ");
            
            
            elsif ch4b = '/' then                                         --
               ada.text_IO.put(output, ch4b);
               ada.text_IO.put(output, " ");
            
               total := total + 10;
               if ch5a = 'X' then                                    --
                  total := total + 10;
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               else
                  total := total + (character'pos(ch5a) - 48);         --
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               end if;
            
            
            else
               total := total + (character'pos(ch4a) -48);
               ada.text_IO.put(output, ch4b); 
               ada.text_IO.put(output, " ");                                       --
               total := total + (character'pos(ch4b) - 48);                  --
               ada.integer_text_IO.put(output, total, width => 1);
               ada.text_IO.put(output, " | ");
            end if;
         
         -- frame 5 output --
         
            ada.text_IO.put(output, ch5a); --
            ada.text_IO.put(output, " ");
         
            if ch5a = 'X' then											-- 12 open
               total := total + 10;
               if ch6a = 'X' then										-- 13 open
                  total := total + 10;
                  if ch7a = 'X' then 									-- 14 open
                     total := total + 10;
                  elsif ch7a /= 'X' then									-- else 14
                     total := total + (character'pos(ch7a) - 48);
                  end if;											-- 14 close
               
               elsif ch6b = '/' then												-- else 13
                  total := total + (10);
               else 
                  total := total + (character'pos(ch6a) - 48);
                  total := total + (character'pos(ch6b) - 48);
               
               end if;
               ada.integer_text_IO.put(output, total, width => 1);
               ada.text_IO.put(output, " | ");
            
            elsif ch5b = '/' then
               ada.text_IO.put(output, ch5b);
               ada.text_IO.put(output, " ");
                                                     --
               total := total + 10;
            
               if ch6a = 'X' then                                    --
                  total := total + 10;
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               else
                  total := total + (character'pos(ch6a) - 48);         --
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               end if;
            
            
            else
               total := total + (character'pos(ch5a) -48);
               ada.text_IO.put(output, ch5b); 
               ada.text_IO.put(output, " ");                                      --
               total := total + (character'pos(ch5b) - 48);                  --
               ada.integer_text_IO.put(output, total, width => 1);
               ada.text_IO.put(output, " | ");
            end if;
         
         -- frame 6 output --
         
            ada.text_IO.put(output, ch6a); --
            ada.text_IO.put(output, " ");
         
            if ch6a = 'X' then											-- 12 open
               total := total + 10;
               if ch7a = 'X' then										-- 13 open
                  total := total + 10;
                  if ch8a = 'X' then 									-- 14 open
                     total := total + 10;
                  elsif ch8a /= 'X' then									-- else 14
                     total := total + (character'pos(ch8a) - 48);
                  end if;											-- 14 close
               
               elsif ch7b = '/' then												-- else 13
                  total := total + (10);
               else 
                  total := total + (character'pos(ch7a) - 48);
                  total := total + (character'pos(ch7b) - 48);
                  
               end if;
            
            elsif ch6b = '/' then
               ada.text_IO.put(output, ch6b);
               ada.text_IO.put(output, " ");
                                         --
               total := total + 10;
               if ch7a = 'X' then                                    --
                  total := total + 10;
               
               else
                  total := total + (character'pos(ch7a) - 48);         --
               
               end if;
            
            
            else
               total := total + (character'pos(ch6a) -48);
               ada.text_IO.put(output, ch6b);   
               ada.text_IO.put(output, " ");                                    --
               total := total + (character'pos(ch6b) - 48);                  --
            
            end if;
            ada.integer_text_IO.put(output, total, width => 1);
            ada.text_IO.put(output, " | ");
         
         
         -- frame 7 output --
         
            ada.text_IO.put(output, ch7a); --
            ada.text_IO.put(output, " ");
         
            if ch7a = 'X' then											-- 12 open
               total := total + 10;
               if ch8a = 'X' then										-- 13 open
                  total := total + 10;
                  if ch9a = 'X' then 									-- 14 open
                     total := total + 10;
                  elsif ch9a /= 'X' then									-- else 14
                     total := total + (character'pos(ch9a) - 48);
                  end if;											-- 14 close
               
               elsif ch8b = '/' then												-- else 13
                  total := total + (10);
               else 
                  total := total + (character'pos(ch8a) - 48);
                  total := total + (character'pos(ch8b) - 48);
                  
               end if;
               ada.integer_text_IO.put(output, total, width => 1);
               ada.text_IO.put(output, " | ");
            
            elsif ch7b = '/' then     
               ada.text_IO.put(output, ch7b);
               ada.text_IO.put(output, " ");
                                    --
               total := total + 10;
               if ch8a = 'X' then                                    --
                  total := total + 10;
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               else
                  total := total + (character'pos(ch8a) - 48);         --
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               end if;
            
            
            else
               total := total + (character'pos(ch7a) -48);
               ada.text_IO.put(output, ch7b); 
               ada.text_IO.put(output, " ");                                       --
               total := total + (character'pos(ch7b) - 48);                  --
               ada.integer_text_IO.put(output, total, width => 1);
               ada.text_IO.put(output, " | ");
            end if;
         
         -- frame 8 output --
         
            ada.text_IO.put(output, ch8a); --
            ada.text_IO.put(output, " ");
         
            if ch8a = 'X' then											-- 12 open
               total := total + 10;
               if ch9a = 'X' then										-- 13 open
                  total := total + 10;
                  if ch10a = 'X' then 									-- 14 open
                     total := total + 10;
                  elsif ch10a /= 'X' then									-- else 14
                     total := total + (character'pos(ch10a) - 48);
                  end if;											-- 14 close
               
               elsif ch9b = '/' then												-- else 13
                  total := total + (10);
               else 
                  total := total + (character'pos(ch9a) - 48);
                  total := total + (character'pos(ch9b) - 48);
                  
               end if;
               ada.integer_text_IO.put(output, total, width => 1);
               ada.text_IO.put(output, " | ");
            
            elsif ch8b = '/' then    
               ada.text_IO.put(output, ch8b);
               ada.text_IO.put(output, " ");
                                     --
               total := total + 10;
               if ch9a = 'X' then                                    --
                  total := total + 10;
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               else
                  total := total + (character'pos(ch9a) - 48);         --
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               end if;
            
            
            else
               total := total + (character'pos(ch8a) -48);
               ada.text_IO.put(output, ch8b);
               ada.text_IO.put(output, " ");                                        --
               total := total + (character'pos(ch8b) - 48);                  --
               ada.integer_text_IO.put(output, total, width => 1);
               ada.text_IO.put(output, " | ");
            end if;
         
         -- frame 9 output --
         
            ada.text_IO.put(output, ch9a); --
            ada.text_IO.put(output, " ");
         
            if ch9a ='X' then     --
               total := total + 10;
               if ch10a = 'X' then  --
                  total := total + 10;
                  if ch10b = 'X' then  --
                     total := total + 10;
                  else 
                     total := total + (character'pos(ch10b) - 48); --
                  end if;
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               elsif ch10b = '/' then                             --
                  total := total - (character'pos(ch10a) - 48); --
                  total := total + 10;
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               else
                  total := total + (character'pos(ch10a) -48) + (character'pos(ch10b) -48);
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               
               end if;
            
            elsif ch9b = '/' then  
               ada.text_IO.put(output, ch9b);
               ada.text_IO.put(output, " ");
                                       --
               total := total + 10;
               if ch10a = 'X' then                                    --
                  total := total + 10;
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               else
                  total := total + (character'pos(ch10a) - 48);         --
                  ada.integer_text_IO.put(output, total, width => 1);
                  ada.text_IO.put(output, " | ");
               end if;
            
            
            else
               total := total + (character'pos(ch9a) -48);
               ada.text_IO.put(output, ch9b); 
               ada.text_IO.put(output, " ");                                       --
               total := total + (character'pos(ch9b) - 48);                  --
               ada.integer_text_IO.put(output, total, width => 1);
               ada.text_IO.put(output, " | ");
            end if;
         
         
         -- frame 10 output --
         
            ada.text_IO.put(output, ch10a);
            ada.text_IO.put(output, " ");
            ada.text_IO.put(output, ch10b); --
            
         
            if ch10a ='X' then
            
               ada.text_IO.put(output, " ");
               ada.text_IO.put(output, ch10c);     --
               total := total + 10;
               if ch10b = 'X' then  --
                  total := total + 10;
                  if ch10c = 'X' then  --
                     total := total + 10;
                  else 
                     total := total + (character'pos(ch10c) - 48);
                       --
                  end if;
                  ada.text_IO.put(output, " | ");
                  ada.integer_text_IO.put(output, total, width => 1);
               
               else 
                  if ch10c = '/' then                             --
                     total := total + 10;
                  else
                  total := total + (character'pos(ch10b) - 48)  + (character'pos(ch10c) - 48);
                  end if;
                  ada.text_IO.put(output, " | ");
                  ada.integer_text_IO.put(output, total, width => 1);
               
               end if;
            
            elsif ch10b = '/' then
               ada.text_IO.put(output, " ");
               ada.text_IO.put(output, ch10c);                                         --
               total := total + 10;
               if ch10c = 'X' then                                    --
                  total := total + 10;
                  ada.text_IO.put(output, " | ");
                  ada.integer_text_IO.put(output, total, width => 1);
               
               else
                  total := total + (character'pos(ch10c) - 48);
                  ada.text_IO.put(output, " | ");         --
                  ada.integer_text_IO.put(output, total, width => 1);
               
               end if;
            
            
            else
               total := total + (character'pos(ch10a) -48);                                        --
               total := total + (character'pos(ch10b) - 48);                  --
               ada.text_IO.put(output, " | ");
               ada.integer_text_IO.put(output, total, width => 1);
            
            end if;
            ada.text_IO.new_line(output);
         -- end output --
         end if;
      
      end Game; -- end game procedure 
   
   
   begin -- bowling --
      Ada.Text_IO.open(output, Ada.Text_IO.out_file, "output7.txt");
      loop
         exit when fendf = 1;
      
      
         Game;
      
      end loop;
   
   
   
   
   
   
   
   end bowling; -- end program
   
   
   
   
   
   
   
   -- need to work on  Spare math, check all other math and spacing.