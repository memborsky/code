WITH Ada.Text_IO;
WITH Ada.Integer_Text_IO;
PACKAGE BODY Screen IS
------------------------------------------------------------------
--| Procedures for drawing pictures on ANSI Terminal Screen
--| These procedures will work correctly only if the actual
--| terminal is ANSI compatible. ANSI.SYS on a DOS machine
--| will suffice.
--| Author: Michael B. Feldman, The George Washington University 
--| Last Modified: September 1995                                     
------------------------------------------------------------------

  PROCEDURE Beep IS
  BEGIN
    Ada.Text_IO.Flush;
    Ada.Text_IO.Put (Item => ASCII.BEL);
  END Beep;

  PROCEDURE ClearScreen IS
  BEGIN
    Ada.Text_IO.Put (Item => ASCII.ESC);
    Ada.Text_IO.Put (Item => "[2J");
    Ada.Text_IO.Flush;
  END ClearScreen;

  PROCEDURE MoveCursor (To: IN Position) IS
  BEGIN                                                
    Ada.Text_IO.Flush;
    Ada.Text_IO.Put (Item => ASCII.ESC);
    Ada.Text_IO.Put ("[");
    Ada.Integer_Text_IO.Put (Item => To.Row, Width => 1);
    Ada.Text_IO.Put (Item => ';');
    Ada.Integer_Text_IO.Put (Item => To.Column, Width => 1);
    Ada.Text_IO.Put (Item => 'f');
  END MoveCursor;  

END Screen;
