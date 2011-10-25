PACKAGE Screen IS
------------------------------------------------------------------
--| Procedures for drawing pictures on ANSI Terminal Screen
--| Author: Michael B. Feldman, The George Washington University 
--| Last Modified: October 1995                                     
------------------------------------------------------------------

  ScreenHeight : CONSTANT Integer := 50;
  ScreenWidth : CONSTANT Integer := 80;

  SUBTYPE Height IS Integer RANGE 1..ScreenHeight;
  SUBTYPE Width  IS Integer RANGE 1..ScreenWidth;

  TYPE Position IS RECORD
    Row   : Height := 1;
    Column: Width := 1;
  END RECORD;

  PROCEDURE Beep; 
  -- Pre:  none
  -- Post: the terminal beeps once
  
  PROCEDURE ClearScreen; 
  -- Pre:  none
  -- Post: the terminal screen is cleared
  
  PROCEDURE MoveCursor (To: IN Position);
  -- Pre:  To is defined
  -- Post: the terminal cursor is moved to the given position
  
END Screen;   
