WITH Windows, Screen;

----------------------------------
--| This program prints a window to the screen.
--| Then it puts a window inside of a window.
--|
--| Due: September 8th, 2004
--|
--| Author: Matt Emborsky
----------------------------------

PROCEDURE window IS

	window_1 : Windows.Window;  -- sets name for first window
	window_2 : Windows.Window;  -- sets name for second window

BEGIN  -- beginning of program

	Screen.ClearScreen;  -- clears the whole screen/terminal

	window_1 := Windows.Open(UpperLeft => (5,5), Height => 12, Width => 52);
	window_2 := Windows.Open(UpperLeft => (8,8), Height => 8, Width => 12);

	-- setup and output data in the first window
	Windows.Borders(window_1, '+', '*', '*');
	Windows.Title(window_1, "CS1410 Project 0 Window", '-');
	Windows.MoveCursor(window_1, (4, 10));
	Windows.Put(window_1, "This is a window.");
	Windows.MoveCursor(window_1, (7, 30));
	Windows.Put(window_1, "Good software engineers use Ada packages.");
	Windows.MoveCursor(window_1, (2,10));
	Windows.Put(window_1, "This is row 3.");
	Windows.MoveCursor(window_1, (4,10));
	Windows.Put(window_1, "Message of my own.");

	-- setup and output data in the second window
	Windows.Borders(window_2, 'X', 'X', 'X');
	Windows.Put(window_2, "testing");
	Windows.Put(window_2, "testing");
	Windows.Put(window_2, "testing");
	Windows.New_Line(window_2);
	Windows.Put(window_2, "testing");
	Windows.Put(window_2, "testing");
	Windows.Put(window_2, "testing");
	Windows.New_Line(window_2);
	Windows.Put(window_2, "testing");
	Windows.Put(window_2, "this is the end.");

	Screen.MoveCursor((20,1));

END window;
