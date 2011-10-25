With Ada.Text_IO, Ada.Integer_Text_IO, Ada.Long_Long_Integer_Text_IO, Ada.Calendar, Ada.Float_Text_IO;
Procedure fib_numbers IS
----------------------------------------------
--|					  |--
--| Matt Emborsky                         |--
--| mlemborsky01.prog2			  |--
--| Due Date 10/6/04			  |--
--|					  |--
--| Program calculates fibinaticy numbers |--
--| using both iterative and recursive    |--
--| calling functions.  It also keeps the |--
--| time for the calls to happen.	  |--
--| 					  |--
---------------------------------------------

  --=============--
  --| Variables |--
  --=============--
---------------------------------

fib_it : Long_Long_Integer;
fib_re : Long_Long_Integer;
time_b_it, time_a_it, time_t_it, time_b_re, time_a_re, time_t_re : Float;
calls_re : Long_Long_Integer := 1;
find : Integer;
ending : Boolean := False;
now : Ada.Calendar.Time;
null_int : Integer;
ext : String(1..2);
org_find : Integer;
joke : Integer;

---------------------------------

  --============--
  -- Procedures --
  --============--

---------------------------------

--==========--
-- Get data --
--==========--

Procedure clear_screen IS

Begin

  Ada.Text_IO.put(ASCII.ESC & "[2J");
  Ada.Text_IO.flush;
  Ada.Text_IO.put(ASCII.ESC & "[0;0f");

End clear_screen;

Procedure get_data (find : in out Integer; ending : in out Boolean) IS

Begin

  -- Get Data for calculations.

  Ada.Text_IO.put("Please input the Fibonacci number you want - ");
  Ada.Integer_Text_IO.get(find);
  Ada.Text_IO.new_line;

  -- Check to see if data is equal to or less than zero.
  If find <= 0 Then
    ending := True;
  End If;

End get_data;

--------------------------

--=============--
-- Output Data --
--=============--

--------------------------

Procedure put_data 	(find : in out Integer;
      fib_it : in out Long_Long_Integer;
      fib_re : in out	Long_Long_Integer;
      time_it : Float;
      time_re : Float;
      putting : Character) IS

Begin

  -- Output all the data that is known or has been found.
  If putting = 'i' Then
    Ada.Text_IO.put("You looked for the ");
    Ada.Integer_Text_IO.put(find, 1);
    If find NOT IN 10..20 Then
      If find mod 10 = 1 Then
        ext := "st";
      Elsif find mod 10 = 2 Then
        ext := "nd";
      Elsif find mod 10 = 3 Then
        ext := "rd";
      Else
        ext := "th";
      End If;
    End If;
    Ada.Text_IO.put_line(ext & " Fibonacci number.");
    Ada.Text_IO.new_line;
    Ada.Text_IO.put_line("Statistical Output:");
    Ada.Text_IO.new_line(2);
    Ada.Text_IO.put("The ");
    Ada.Integer_Text_IO.put(find, 1);
                If find NOT IN 10..20 Then
                        If find mod 10 = 1 Then
                                ext := "st";
                        Elsif find mod 10 = 2 Then
                                ext := "nd";
                        Elsif find mod 10 = 3 Then
                                ext := "rd";
      Else
        ext := "th";
                        End If;
                End If;
    Ada.Text_IO.put(ext & " Fibonacci number calculated iteratively is ");
    Ada.Long_Long_Integer_Text_IO.put(fib_it, 1);
    Ada.Text_IO.put_line(".");
    Ada.Text_IO.put("It took ");
    Ada.Float_Text_IO.put(time_it, fore => 3, aft => 15, exp => 0);
          Ada.Text_IO.put_line(" seconds to calculate iteratively.");
    Ada.Text_IO.new_line(2);
  End If;

  If putting = 'r' Then
    Ada.Text_IO.put("The ");
    Ada.Integer_Text_IO.put(find, 1);
    If find NOT IN 10..20 Then
      If find mod 10 = 1 Then
        ext := "st";
      Elsif find mod 10 = 2 Then
        ext := "nd";
      Elsif find mod 10 = 3 Then
        ext := "rd";
      Else
        ext := "th";
      End If;
    End If;
    Ada.Text_IO.put(ext & " Fibonacci number calculated recursively is ");
    Ada.Long_Long_Integer_Text_IO.put(fib_re, 1);
    Ada.Text_IO.put(".");
    Ada.Text_IO.new_line;
    Ada.Text_IO.put("It took ");
    Ada.Float_Text_IO.put(time_re, fore => 3, aft => 15, exp => 0);
    Ada.Text_IO.put_line(" seconds to calculate recursively.");
    Ada.Text_IO.put("It took ");
    Ada.Long_Long_Integer_Text_IO.put(calls_re, 1);
    Ada.Text_IO.put_line(" recursive function calls.");
    Ada.Text_IO.new_line(5);
    Ada.Text_IO.put_line("Welcome to the world of windows:");
    Ada.Text_IO.new_line;
    Ada.Text_IO.put("Since we can't make a system to with stand all that recursive calling,  we are just going to have to lock up your system in - ");
  End If;

End put_data;

-----------------------------------

--==================--
-- Find iteratively --
--==================--

-----------------------------------

Procedure iteratively (find : Integer; fib_it : out Long_Long_Integer) IS

Type previo IS Array (1..3) OF Long_Long_Integer;
previous : previo;

Begin

  If find = 1 Then
    fib_it := 1;
  Elsif find = 2 Then
    fib_it := 1;
  Else
    previous(1) := 1;
    previous(2) := 1;

    For count IN 3 .. find Loop

      previous(3) := previous(2) + previous(1);
      previous(1) := previous(2);
      previous(2) := previous(3);

    End Loop;

  fib_it := previous(3);
  End If;


End iteratively;

------------------------------------------------------------------

  --===========--
  -- Functions --
  --===========--

------------------------------------------------------------------

--==================--
-- Find recursively --
--==================--

----------------------------------

Function recursively (find : in Integer) Return Long_Long_Integer IS

Begin

  calls_re := calls_re + 1;

  If find = 1 Then
    return 1;
  Elsif find = 2 Then
    return 1;
  Else
    return ((recursively(find-1)) + (recursively(find-2)));
  End If;

End recursively;


--=======--
-- Start --
--=======--

-----------------------

Procedure start IS

Begin

  clear_screen;
  find := 0;
  ending := False;
  fib_it := 0;
  fib_re := 0;
  time_a_it := 0.0;
  time_b_it := 0.0;
  time_t_it := 0.0;
  time_a_re := 0.0;
  time_b_re := 0.0;
  time_t_re := 0.0;
  calls_re := 0;

End start;

----------------------------------------------------------------------------

Begin

  start;
  get_data(find, ending);
  If not ending Then
    now := Ada.Calendar.Clock;
    time_b_it := Float(Ada.Calendar.Seconds(now));
    iteratively(find, fib_it);
    now := Ada.Calendar.Clock;
    time_a_it := Float(Ada.Calendar.Seconds(now));
    time_t_it := time_a_it - time_b_it;
    put_data(find, fib_it, fib_re, time_t_it, time_t_re, 'i');
    now := Ada.Calendar.Clock;
    time_b_re := Float(Ada.Calendar.Seconds(now));
    org_find := find;
    fib_re := recursively(find);
    now := Ada.Calendar.Clock;
    time_a_re := Float(Ada.Calendar.Seconds(now));
    time_t_re := time_a_re - time_b_re;
    put_data(find, fib_it, fib_re, time_t_it, time_t_re, 'r');

    joke := 11;
    For x IN 1..10 Loop
      joke := joke - 1;
      For y IN 1..99990000 Loop
        null_int := y * x + (y*2);
      End Loop;
      If joke >= 8 Then
        Ada.Integer_Text_IO.put(joke, 1);
        Ada.Text_IO.put(" ");
      End If;
    End Loop;
    Ada.Text_IO.put("done");
    For x IN 1..1000000 Loop
      null;
    End Loop;
      clear_screen;
    fib_numbers;
  End If;

  Ada.Text_IO.put("Thank you for using linux, the programmers operating system!!!");

End fib_numbers;
