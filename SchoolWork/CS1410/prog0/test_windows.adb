WITH Windows;
WITH Screen;
PROCEDURE Test_Windows IS
------------------------------------------------------------------
--| Very simple test of Windows package
--| Author: Michael B. Feldman, The George Washington University 
--| Last Modified: October 1995                                     
------------------------------------------------------------------

  W1: Windows.Window;
  W2: Windows.Window;
  W3: Windows.Window;
  
BEGIN -- Test_Windows

  Screen.ClearScreen;

  W1 := Windows.Open(UpperLeft => (Row => 2, Column => 5),
                     Height => 10, Width => 18);
  W2 := Windows.Open(UpperLeft => (Row => 15, Column => 20),
                     Height => 7, Width => 7);

  Windows.Borders(W => W1, Corner => '+',Down => '|', Across => '-');
  Windows.Title(W1, "Window One", '_');
  Windows.Put(W1, "This is the first string going in the window.");
  Windows.Put(W1, "And this is the second one.");

  Windows.Put(W2, "This is a window without a border or a title.");

  W3 := Windows.Open(UpperLeft => (Row => 5, Column => 35),
                     Height => 8, Width => 25);

  Windows.New_Line(W1);
  Windows.Put(W1, "Bye.");

  Windows.Borders(W => W3, Corner => '*',Down => '*', Across => '*');
  Windows.Title(W3, "Window Three", ';');
  Windows.Put(W3, "This is the first string going in the third window.");
  Windows.Put(W3, "And this is the second one.");
  Windows.New_Line(W3);
  Windows.Put(W3, "So long.");
  Screen.MoveCursor(To => (Row => 23, Column => 1));
 
END Test_Windows;
