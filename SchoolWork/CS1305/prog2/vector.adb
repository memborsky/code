----------------------------------------------------------------
--| prog2, Vectors
--| Matt Emborsky
--| CS1305AA
--| Due Date: Friday 2/16/2004
----------------------------------------------------------------
----------------------------------------------------------------
--| This program will take an input by the user and generate the
--| output in Leagues, Miles, Yards, and Feet.  It also calculates
--| the unknown vector and prints that out in the same format.
--| Author: Matt Emborsky, Student at Indiana Institute of Technology
--|           February 2004
----------------------------------------------------------------

WITH ada.TEXT_IO, -- use the standard text I/O package
ada.INTEGER_TEXT_IO; -- use the standard integer I/O package

PROCEDURE vector IS

-- constants, This section defines all the constents that are used in the program

inches_feet : constant INTEGER := 12;  --this defines the amount of feet in inches
inches_yard : constant INTEGER := 36;  --this defines the amount of yards in inches
inches_mile : constant INTEGER := 63360;  --this defines the amount of miles in inches
inches_league : constant INTEGER := 190080;  --this defines the amount of leagues in inches
feet_yard : constant INTEGER := 3;  --this defines the amount of yards in feet
yard_mile : constant INTEGER := 1760;  --this defines the amount of miles in yards
mile_league : constant INTEGER := 3;  --this defines the amount of leagues in miles


--- variables

AB,
AB_inches,
AB_feet,
AB_yards,
AB_miles,
AB_leagues,
BC,
BC_inches,
BC_feet,
BC_yards,
BC_miles,
BC_leagues,
CD,
CD_inches,
CD_feet,
CD_yards,
CD_miles,
CD_leagues,
AD_inches,
AD_feet,
AD_yards,
AD_miles,
AD_leagues: INTEGER := 0; -- make integer variables

BEGIN

--- input

--first input

ada.TEXT_IO.put_line ("Please input the amount of inches for the first vector - "); --prints the line asking for text input
ada.INTEGER_TEXT_IO.get(AB); --gets the value for the vector AB

-- second input

ada.TEXT_IO.put_line ("Please input the amount of inches for the second vector - ");
ada.INTEGER_TEXT_IO.get(BC);

-- third input

ada.TEXT_IO.put_line ("Please input the amount of inches for the first vector - ");
ada.INTEGER_TEXT_IO.get(CD);

-- end input



-- inputs the original values in to the values of each known vector

AB_inches := AB;
BC_inches := BC;
CD_inches := CD;


-- calculations for AB, this section finds the distnace in the simplest form

AB_leagues := AB_inches / inches_league;
AB_inches := AB_inches REM inches_league;

AB_miles := AB_inches / inches_mile;
AB_inches := AB_inches REM inches_mile;

AB_yards := AB_inches / inches_yard;
AB_inches := AB_inches REM inches_yard;

AB_feet := AB_inches / inches_feet;
AB_inches := AB_inches REM inches_feet;



-- calculations for BC, this section finds the distnace in the simplest form

BC_leagues := BC_inches / inches_league;
BC_inches := BC_inches REM inches_league;

BC_miles := BC_inches / inches_mile;
BC_inches := BC_inches REM inches_mile;

BC_yards := BC_inches / inches_yard;
BC_inches := BC_inches REM inches_yard;

BC_feet := BC_inches / inches_feet;
BC_inches := BC_inches REM inches_feet;



-- Calculations for CD, this section finds the distnace in the simplest form

CD_leagues := CD_inches / inches_league;
CD_inches := CD_inches REM inches_league;

CD_miles := CD_inches / inches_mile;
CD_inches := CD_inches REM inches_mile;

CD_yards := CD_inches/inches_yard;
CD_inches := CD_inches REM inches_yard;

CD_feet := CD_inches / inches_feet;
CD_inches := CD_inches REM inches_feet;



-- AD output and calculations, this section finds the distances of the unkown vector, and finds the distnace in the simplest form

AD_inches := AB_inches + BC_inches + CD_inches;
AD_feet := AD_inches / inches_feet;
AD_inches := AD_inches REM inches_feet;

AD_feet := AD_feet + AB_feet + BC_feet + CD_feet;
AD_yards := AD_feet / feet_yard;
AD_feet := AD_feet REM feet_yard;

AD_yards := AD_yards + AB_yards + BC_yards + CD_yards;
AD_miles := AD_yards / yard_mile;
AD_yards := AD_yards REM yard_mile;

AD_miles := AD_miles + AB_miles + BC_miles + CD_miles;
AD_leagues := AD_miles / mile_league;
AD_miles := AD_miles REM mile_league;

AD_leagues := AD_leagues + AB_leagues + BC_leagues + CD_leagues;

-- end AD calculations start AD output, this ends the secction on find the distances of the unkown vector

-- end of calculations and beginning of the output

ada.TEXT_IO.put_line ("Vector  Leagues    Miles    Yards     Feet     Inches");
ada.TEXT_IO.put_line ("=====|==========|========|=========|=========|=========");

--------------- AB output -----------
ada.TEXT_IO.put("AB   |");
ada.INTEGER_TEXT_IO.put(AB_leagues, width =>10);
ada.TEXT_IO.put("|");
ada.INTEGER_TEXT_IO.put(AB_miles, width =>8);
ada.TEXT_IO.put("|");
ada.INTEGER_TEXT_IO.put(AB_yards, width =>9);
ada.TEXT_IO.put("|");
ada.INTEGER_TEXT_IO.put(AB_feet, width =>9);
ada.TEXT_IO.put("|");
ada.INTEGER_TEXT_IO.put(AB_inches, width =>9);
ada.TEXT_IO.new_line;

--------------- BC output -----------
ada.TEXT_IO.put("BC   |");
ada.INTEGER_TEXT_IO.put(BC_leagues, width =>10);
ada.TEXT_IO.put("|");
ada.INTEGER_TEXT_IO.put(BC_miles, width =>8);
ada.TEXT_IO.put("|");
ada.INTEGER_TEXT_IO.put(BC_yards, width =>9);
ada.TEXT_IO.put("|");
ada.INTEGER_TEXT_IO.put(BC_feet, width =>9);
ada.TEXT_IO.put("|");
ada.INTEGER_TEXT_IO.put(BC_inches, width =>9);
ada.TEXT_IO.new_line; -- skip a line on the screen

--------------- CD output -----------
ada.TEXT_IO.put("CD   |");
ada.INTEGER_TEXT_IO.put(CD_leagues, width =>10);
ada.TEXT_IO.put("|");
ada.INTEGER_TEXT_IO.put(CD_miles, width =>8);
ada.TEXT_IO.put("|");
ada.INTEGER_TEXT_IO.put(CD_yards, width =>9);
ada.TEXT_IO.put("|");
ada.INTEGER_TEXT_IO.put(CD_feet, width =>9);
ada.TEXT_IO.put("|");
ada.INTEGER_TEXT_IO.put(CD_inches, width =>9);
ada.TEXT_IO.new_line; -- skip a line on the screen

--------------- AD output -----------
ada.TEXT_IO.put_line ("=====|==========|========|=========|=========|=========");
ada.TEXT_IO.put("AD   |");
ada.INTEGER_TEXT_IO.put(AD_leagues, width =>10);
ada.TEXT_IO.put("|");
ada.INTEGER_TEXT_IO.put(AD_miles, width =>8);
ada.TEXT_IO.put("|");
ada.INTEGER_TEXT_IO.put(AD_yards, width =>9);
ada.TEXT_IO.put("|");
ada.INTEGER_TEXT_IO.put(AD_feet, width =>9);
ada.TEXT_IO.put("|");
ada.INTEGER_TEXT_IO.put(AD_inches, width =>9);

-- end of hte printing

END vector; --END of program
