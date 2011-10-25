----------------------------------------------------------------
--| Assignment 3, Metric Program
--| Matt Emborsky
--| CS1305AA
--| Due Date: Monday 2/23/2004
----------------------------------------------------------------
----------------------------------------------------------------
--| This program will take your inputed hieght and wieght in inches
--| and out put it in metrics form.  Input pounds, output kilograms.
--| Input feet and inches
--| 
--| 
--| 
--| 
----------------------------------------------------------------
With Ada.Text_IO, -- uses the standard text I/O package
Ada.Float_Text_IO;  -- uses the standard float I/O package

Procedure metric Is

-- Constant, This section defines all the constents that are used in the program

Feet_To_Inches : Constant Float := 12.000;  --this sets the conversion factor between feet to inches
Inches_To_Meters : Constant Float := 39.40;  --this sets the conversion factor between meters to inches
Kilograms_To_Pounds : Constant Float := 0.4539; -- this sets the conversion factor between pounds to kilograms

-- VARIABLE, This section defines all the variables that are using this program

Weight_In_Pounds,
Weight_In_Kilograms,
Height_In_Feet,
Height_Inches,
Height_In_Inches,
Height_In_Meters : Float := 0.000; -- makes integers equal 0

Begin -- begins program

  -- beginning of input section
  Ada.Text_IO.Put("Please input your weight in pounds - ");  -- prints line asking for weight
  Ada.Float_Text_IO.get (Weight_In_Pounds); -- gets the weight
  Ada.Text_IO.New_Line;

  Ada.Text_IO.Put("Please input your height in feet - "); -- prints line asking for height in feet
  Ada.Float_Text_IO.get (Height_In_Feet); -- gets height in feet
  Ada.Text_IO.New_Line;

  Ada.Text_IO.Put("Please input your height in inches - "); -- prints line asking for height in inches
  Ada.Float_Text_IO.get (Height_Inches); -- gets height in inches
  Ada.Text_IO.New_Line;
  -- ending of input section

  -- beginning of conversion section
  Weight_In_Kilograms := Weight_In_Pounds * Kilograms_To_Pounds;
  Height_In_Inches := Height_Inches + (Height_In_Feet * Feet_To_Inches);
  Height_In_Meters := Height_In_Inches / Inches_To_Meters;
  -- ending of conversion section

  -- beginning of output
  Ada.Text_IO.New_Line;  -- blank line
  Ada.Text_IO.New_Line;  -- blank line
  Ada.Text_IO.New_Line;  -- blank line

  -- starts printing for weight
  Ada.Float_Text_IO.Put(Weight_In_Pounds, aft => 3, exp => 0); -- prints original weight in pounds
  Ada.Text_IO.Put(" pounds = ");
  Ada.Float_Text_IO.Put(Weight_In_Kilograms, aft => 3, exp => 0); -- prints weight in kilograms
  Ada.Text_IO.Put_Line(" kilograms");
  -- ends printing for weight

  -- starts printing for height
  Ada.Float_Text_IO.Put(Height_In_Feet, aft => 2, exp => 0); -- prints height in feet
  Ada.Text_IO.Put(" feet, ");
  Ada.Float_Text_IO.Put(Height_Inches, aft => 2, exp => 0); -- prints height in inches
  Ada.Text_IO.Put(" inches = ");
  Ada.Float_Text_IO.Put(Height_In_Meters, aft => 2, exp => 0); -- prints height in meters
  Ada.Text_IO.Put(" meters");
  -- ends printing for height

End metric; -- ends program
