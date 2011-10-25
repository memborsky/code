----------------------------------------------------------------
--| Assignment 4, Order Forum
--| Matt Emborsky
--| CS1305AA
--| Due Date: Wensday 3/3/2004
----------------------------------------------------------------
----------------------------------------------------------------
--| This program will call the operator to input data for an order
--| forum easier by supplying a standard layout with a easy user
--| interface.
--|
--| Author: Matt Emborsky, Indiana Institute of Technology
--|                   March 2004
----------------------------------------------------------------
With Ada.Text_IO, -- use the standard TEXT I/O package
Ada.Float_Text_IO, -- use the standard Float I/O package
Ada.Calendar,
Ada.Integer_Text_IO,
Screen;

Procedure billing Is

  -- Constants --
  CDRW : Constant Float := 9.99;
  DDS : Constant Float := 25.19;
  USBSTICK : Constant Float := 30.00;
  DIMM : Constant Float := 119.42;
  Mouse : Constant Float := 22.78;
  shipping : Constant Float := 5.50;
  tax : Constant Float := 0.065;

  -- variables --
  Output_File_Pointer : Ada.Text_IO.File_type;

  Item_Number_1,
  Item_Number_2,
  Item_Number_3: Float := 0.0;

  sale1,
  sale2,
  sale3,
  totalsale,
  salestax,
  SH,
  total: Float := 0.00;

  name_length,
  address1_length,
  address2_length,
  address3_length,
  phone_length,
  item1_length,
  item2_length,
  item3_length : Natural;

  item1,
  item2,
  item3,
  Name,
  Address1,
  Address2,
  Address3 : string(1..30);

  Phone : string(1..11);

  V,
  item11,
  item22,
  item33 : INTEGER := 0;

  Current_Time : Ada.CALENDAR.time;
  Day : Ada.CALENDAR.day_number;
  Month : Ada.CALENDAR.month_number;
  Year : Ada.CALENDAR.year_number;

begin

  Ada.Text_IO.Open(Output_File_Pointer, Ada.Text_IO.out_file, "output.txt");

  Current_Time := Ada.Calendar.Clock;
  Month := Ada.Calendar.Month(Date => Current_Time);
  Day   := Ada.Calendar.Day  (Date => Current_Time);
  Year  := Ada.Calendar.Year (Date => Current_Time);


  Screen.clearscreen;

-- putting form --
  Ada.Text_IO.put("Date: ");
  Ada.Integer_Text_IO.put(Month,1);
  Ada.Text_IO.put("/");
  Ada.Integer_Text_IO.put(Day,1);
  Ada.Text_IO.put("/");
  Ada.Integer_Text_IO.put(Year,1);
  Ada.Text_IO.new_line;
  Ada.Text_IO.new_line;
  Ada.Text_IO.new_line;
  Ada.Text_IO.put("Name:");
  Ada.Text_IO.new_line;
  Ada.Text_IO.new_line;
  Ada.Text_IO.put("Address:");
  Ada.Text_IO.new_line;
  Ada.Text_IO.new_line;
  Ada.Text_IO.new_line;
  Ada.Text_IO.new_line;
  Ada.Text_IO.new_line;
  Ada.Text_IO.put("Phone:");
  Ada.Text_IO.new_line;
  Ada.Text_IO.new_line;
  Ada.Text_IO.put ("Item:                         Number:                  Price @:              Sale:");
  Ada.Text_IO.new_line;
  Ada.Text_IO.new_line;
  Ada.Text_IO.new_line;
  Ada.Text_IO.new_line;
  Ada.Text_IO.new_line;
  Ada.Text_IO.new_line;
  Ada.Text_IO.new_line;
  Ada.Text_IO.new_line;
  Ada.Text_IO.new_line;
  Ada.Text_IO.put ("                                                                        Total Sale: $");
  Ada.Text_IO.new_line;
  Ada.Text_IO.new_line;
  Ada.Text_IO.put ("                                                                   Sales Tax(6.5%): $");
  Ada.Text_IO.new_line;
  Ada.Text_IO.new_line;
  Ada.Text_IO.put ("                                                             Shipping and Handling: $");
  Ada.Text_IO.new_line;
  Ada.Text_IO.new_line;
  Ada.Text_IO.put ("                                                                         Total Due: $");
  Screen.movecursor(Column => 7, Row => 5);
  Ada.Text_IO.get_line(Name, name_length);
  Screen.movecursor(Column => 1, Row => 8);
  Ada.Text_IO.get_line(Address1, address1_length);
  Screen.movecursor(Column => 1, Row => 9);
  Ada.Text_IO.get_line(Address2, address2_length);
  Screen.movecursor(Column => 1, Row => 10);
  Ada.Text_IO.get_line(Address3, address3_length);
  Screen.movecursor(Column => 8, Row => 12);
  Ada.Text_IO.get_line(Phone, phone_length);

-- item 1 --


  Screen.movecursor(Column => 1, Row => 15);
  Ada.Text_IO.get_line(item1, item1_length);

  if item1(1..item1_length) /= " " then
     Screen.movecursor(Column => 34, Row => 15);
     Ada.Float_Text_IO.get(Item_Number_1);

     Screen.movecursor(Column => 55, Row => 15);
     if item1(1..item1_length) = " " then
        V := V+1;
     elsif item1(1..item1_length) = "CD-RW" then
        Ada.Float_Text_IO.put(CDRW, fore => 4, aft =>2, exp => 0);
     elsif item1(1..item1_length) = "20 GB DDS" then
        Ada.Float_Text_IO.put(DDS, fore => 4, aft =>2, exp => 0);
     elsif item1(1..item1_length) = "128 MB USB Stick" then
        Ada.Float_Text_IO.put(usbstick, fore => 4, aft =>2, exp => 0);
     elsif item1(1..item1_length) = "512MB DIMM" then
        Ada.Float_Text_IO.put(DIMM, fore => 4, aft =>2, exp => 0);
     elsif item1(1..item1_length) = "Wireless Mouse" then
        Ada.Float_Text_IO.put(mouse, fore => 4, aft =>2, exp => 0);
     else Ada.Text_IO.put(" ");
     end if;

     if V /= 1 then
        if item1(1..item1_length) = " " or V = 1 then
           null;
        elsif item1(1..item1_length) = "CD-RW" then
           sale1 := Item_Number_1*CDRW;
        elsif item1(1..item1_length) = "20 GB DDS" then
           sale1 := Item_Number_1*DDS;
        elsif item1(1..item1_length) = "128 MB USB Stick" then
           sale1 := Item_Number_1*usbstick;
        elsif item1(1..item1_length) = "512MB DIMM" then
           sale1 := Item_Number_1*DIMM;
        elsif item1(1..item1_length) = "Wireless Mouse" then
           sale1 := Item_Number_1*mouse;
        else sale1 := 0.00;
        end if;
        Screen.movecursor(Column => 76, Row => 15);
        Ada.Float_Text_IO.put(sale1, fore => 4, aft =>2, exp => 0);
     end if;


     Ada.Text_IO.skip_line;

  -- item 2 --



     Screen.movecursor(Column => 1, Row => 16);
     Ada.Text_IO.get_line(item2, item2_length);

     if V /= 1 then
        if item2(1..item2_length) /= " " then
           Screen.movecursor(Column => 34, Row => 16);
           Ada.Float_Text_IO.get(Item_Number_2);

           Screen.movecursor(Column => 55, Row => 16);
           if item2(1..item2_length) = " "  or V = 1 then
              V := V+1;
           elsif item2(1..item2_length) = "CD-RW" then
              Ada.Float_Text_IO.put(CDRW, fore => 4, aft =>2, exp => 0);
           elsif item2(1..item2_length) = "20 GB DDS" then
              Ada.Float_Text_IO.put(DDS, fore => 4, aft =>2, exp => 0);
           elsif item2(1..item2_length) = "128 MB USB Stick" then
              Ada.Float_Text_IO.put(usbstick, fore => 4, aft =>2, exp => 0);
           elsif item2(1..item2_length) = "512MB DIMM" then
              Ada.Float_Text_IO.put(DIMM, fore => 4, aft =>2, exp => 0);
           elsif item2(1..item2_length) = "Wireless Mouse" then
              Ada.Float_Text_IO.put(mouse, fore => 4, aft =>2, exp => 0);
           else Ada.Text_IO.put(" ");
           end if;

           if item2(1..item2_length) = " "  or V = 1 then
              null;
           elsif item2(1..item2_length) = "CD-RW" then sale2 := Item_Number_2*CDRW;
           elsif item2(1..item2_length) = "20 GB DDS" then
              sale2 := Item_Number_2*DDS;
           elsif item2(1..item2_length) = "128 MB USB Stick" then
              sale2 := Item_Number_2*usbstick;
           elsif item2(1..item2_length) = "512MB DIMM" then
              sale2 := Item_Number_2*DIMM;
           elsif item2(1..item2_length) = "Wireless Mouse" then
              sale2 := Item_Number_2*mouse;
           else sale2 := 0.00;
           end if;
           Screen.movecursor(Column => 76, Row => 16);
           Ada.Float_Text_IO.put(sale2, fore => 4, aft =>2, exp => 0);
        end if;


        Ada.Text_IO.skip_line;


     -- item 3 --

        Screen.movecursor(Column => 1, Row => 17);
        Ada.Text_IO.get_line(item3, item3_length);
        if item3(1..item3_length) /= " " then
           Screen.movecursor(Column => 34, Row => 17);
           Ada.Float_Text_IO.get(Item_Number_3);
           Screen.movecursor(Column => 55, Row => 17);


           if item3(1..item3_length) = " "  or V = 1 then
              V := V+1;
           elsif item3(1..item3_length) = "CD-RW" then
              Ada.Float_Text_IO.put(CDRW, fore => 4, aft =>2, exp => 0);
           elsif item3(1..item3_length) = "20 GB DDS" then
              Ada.Float_Text_IO.put(DDS, fore => 4, aft =>2, exp => 0);
           elsif item3(1..item3_length) = "128 MB USB Stick" then
              Ada.Float_Text_IO.put(usbstick, fore => 4, aft =>2, exp => 0);
           elsif item3(1..item3_length) = "512MB DIMM" then
              Ada.Float_Text_IO.put(DIMM, fore => 4, aft =>2, exp => 0);
           elsif item3(1..item3_length) = "Wireless Mouse" then
              Ada.Float_Text_IO.put(mouse, fore => 4, aft =>2, exp => 0);
           else Ada.Text_IO.put(" ");
           end if;

           if item3(1..item3_length) = " " or V = 1  then
              null;
           elsif item3(1..item3_length) = "CD-RW" then sale3 := Item_Number_3*CDRW;
           elsif item3(1..item3_length) = "20 GB DDS" then
              sale3 := Item_Number_3*DDS;
           elsif item3(1..item3_length) = "128 MB USB Stick" then
              sale3 := Item_Number_3*usbstick;
           elsif item3(1..item3_length) = "512MB DIMM" then
              sale3 := Item_Number_3*DIMM;
           elsif item3(1..item3_length) = "Wireless Mouse" then
              sale3 := Item_Number_3*mouse;
           else sale3 := 0.00;
           end if;
           Screen.movecursor(Column => 76, Row => 17);
           Ada.Float_Text_IO.put(sale3, fore => 4, aft =>2, exp => 0);
        end if;
     end if;
  end if;

-- end individual items --

  totalsale := sale1+sale2+sale3;

  Screen.movecursor(Column => 86, Row => 23);
  Ada.Float_Text_IO.put(totalsale, fore => 4, aft =>2, exp => 0);

  salestax := tax*totalsale;

  Screen.movecursor(Column => 86, Row => 25);
  Ada.Float_Text_IO.put(salestax, fore => 4, aft =>2, exp => 0);

  if item1(1..item1_length) /= " " then
     SH := shipping + (Item_Number_1*0.50) + (Item_Number_2*0.50) + (Item_Number_3*0.50);
  else SH := 0.00;
  end if;

  Screen.movecursor(Column => 86, Row => 27);
  Ada.Float_Text_IO.put(SH, fore => 4, aft =>2, exp => 0);

  total := SH+salestax+totalsale;

  Screen.movecursor(Column => 86, Row => 29);
  Ada.Float_Text_IO.put(total, fore => 4, aft =>2, exp => 0);



--- file Output_File_Pointer ----


  Ada.Text_IO.put(Output_File_Pointer,"Date: ");
  Ada.Integer_Text_IO.put(Output_File_Pointer,Month,1);
  Ada.Text_IO.put(Output_File_Pointer,"/");
  Ada.Integer_Text_IO.put(Output_File_Pointer,Day,1);
  Ada.Text_IO.put(Output_File_Pointer,"/");
  Ada.Integer_Text_IO.put(Output_File_Pointer,Year,1);
Ada.Text_IO.new_line(Output_File_Pointer);
Ada.Text_IO.new_line(Output_File_Pointer);
Ada.Text_IO.put(Output_File_Pointer,"Name: ");
  Ada.Text_IO.put(Output_File_Pointer,Name(1..name_length));
Ada.Text_IO.new_line(Output_File_Pointer);
  Ada.Text_IO.new_line(Output_File_Pointer);
Ada.Text_IO.put(Output_File_Pointer,"Address:");
  Ada.Text_IO.new_line(Output_File_Pointer);
  Ada.Text_IO.new_line(Output_File_Pointer);
  Ada.Text_IO.put(Output_File_Pointer,Address1(1..address1_length));
  Ada.Text_IO.new_line(Output_File_Pointer);
  Ada.Text_IO.put(Output_File_Pointer,Address2(1..address2_length));
  Ada.Text_IO.new_line(Output_File_Pointer);
  Ada.Text_IO.put(Output_File_Pointer,Address3(1..address3_length));
  Ada.Text_IO.new_line(Output_File_Pointer);
  Ada.Text_IO.new_line(Output_File_Pointer);
  Ada.Text_IO.put(Output_File_Pointer,"Phone: ");
  Ada.Text_IO.put(Output_File_Pointer,Phone(1..phone_length));
  Ada.Text_IO.new_line(Output_File_Pointer);
  Ada.Text_IO.new_line(Output_File_Pointer);
  Ada.Text_IO.put (Output_File_Pointer, "Item:                                    Number:                  Price @:                      Sale:");
  Ada.Text_IO.new_line(Output_File_Pointer);


  Ada.Text_IO.put(Output_File_Pointer,item1(1..item1_length));
  if item1(1..item1_length) = "CD-RW" then
     Ada.Text_IO.put(Output_File_Pointer,"                              ");
  elsif item1(1..item1_length) = "Wireless Mouse" then
     Ada.Text_IO.put(Output_File_Pointer,"                     ");
  elsif item1(1..item1_length) = "20 GB DDS" then
     Ada.Text_IO.put(Output_File_Pointer,"                          ");
  elsif item1(1..item1_length) = "128 MB USB Stick" then
     Ada.Text_IO.put(Output_File_Pointer,"                   ");
  elsif item1(1..item1_length) = "512MB DIMM" then
     Ada.Text_IO.put(Output_File_Pointer,"                         ");
  end if;
  item11 := INTEGER(Item_Number_1);
  Ada.Integer_Text_IO.put(Output_File_Pointer,item11);
  Ada.Text_IO.put(Output_File_Pointer,"                     ");


  if item1(1..item1_length) = "CD-RW" then
     Ada.Float_Text_IO.put(Output_File_Pointer,CDRW, fore => 4, aft =>2, exp => 0);
  elsif item1(1..item1_length) = "20 GB DDS" then
     Ada.Float_Text_IO.put(Output_File_Pointer,DDS, fore => 4, aft =>2, exp => 0);
  elsif item1(1..item1_length) = "128 MB USB Stick" then
     Ada.Float_Text_IO.put(Output_File_Pointer,usbstick, fore => 4, aft =>2, exp => 0);
  elsif item1(1..item1_length) = "512MB DIMM" then
     Ada.Float_Text_IO.put(Output_File_Pointer,DIMM, fore => 4, aft =>2, exp => 0);
  elsif item1(1..item1_length) = "Wireless Mouse" then
     Ada.Float_Text_IO.put(Output_File_Pointer,mouse, fore => 4, aft =>2, exp => 0);
  else Ada.Text_IO.put(" ");
  end if;
  Ada.Text_IO.put(Output_File_Pointer,"                    ");
  Ada.Float_Text_IO.put(Output_File_Pointer,sale1, fore => 4, aft =>2, exp => 0);


  Ada.Text_IO.new_line(Output_File_Pointer);

  Ada.Text_IO.put(Output_File_Pointer,item2(1..item2_length));
  if item2(1..item2_length) /= " " then
     if item2(1..item2_length) = "CD-RW" then
        Ada.Text_IO.put(Output_File_Pointer,"                              ");
     elsif item2(1..item2_length) = "Wireless Mouse" then
        Ada.Text_IO.put(Output_File_Pointer,"                     ");
     elsif item2(1..item2_length) = "20 GB DDS" then
        Ada.Text_IO.put(Output_File_Pointer,"                          ");
     elsif item2(1..item2_length) = "128 MB USB Stick" then
        Ada.Text_IO.put(Output_File_Pointer,"                   ");
     elsif item2(1..item2_length) = "512MB DIMM" then
        Ada.Text_IO.put(Output_File_Pointer,"                         ");
     end if;
     item22 := INTEGER(Item_Number_2);
     Ada.Integer_Text_IO.put(Output_File_Pointer,item22);
     Ada.Text_IO.put(Output_File_Pointer,"                     ");

     if item2(1..item2_length) = "CD-RW" then
        Ada.Float_Text_IO.put(Output_File_Pointer,CDRW, fore => 4, aft =>2, exp => 0);
     elsif item2(1..item2_length) = "20 GB DDS" then
        Ada.Float_Text_IO.put(Output_File_Pointer,DDS, fore => 4, aft =>2, exp => 0);
     elsif item2(1..item2_length) = "128 MB USB Stick" then
        Ada.Float_Text_IO.put(Output_File_Pointer,usbstick, fore => 4, aft =>2, exp => 0);
     elsif item2(1..item2_length) = "512MB DIMM" then
        Ada.Float_Text_IO.put(Output_File_Pointer,DIMM, fore => 4, aft =>2, exp => 0);
     elsif item2(1..item2_length) = "Wireless Mouse" then
        Ada.Float_Text_IO.put(Output_File_Pointer,mouse, fore => 4, aft =>2, exp => 0);
     else Ada.Text_IO.put(" ");
     end if;

     Ada.Text_IO.put(Output_File_Pointer,"                    ");
     Ada.Float_Text_IO.put(Output_File_Pointer,sale2, fore => 4, aft =>2, exp => 0);

  end if;

  Ada.Text_IO.new_line(Output_File_Pointer);

  Ada.Text_IO.put(Output_File_Pointer,item3(1..item3_length));
  if item3(1..item3_length) /= " " then
     if item3(1..item3_length) = "CD-RW" then
        Ada.Text_IO.put(Output_File_Pointer,"                              ");
     elsif item3(1..item3_length) = "Wireless Mouse" then
        Ada.Text_IO.put(Output_File_Pointer,"                     ");
     elsif item3(1..item3_length) = "20 GB DDS" then
        Ada.Text_IO.put(Output_File_Pointer,"                          ");
     elsif item3(1..item3_length) = "128 MB USB Stick" then
        Ada.Text_IO.put(Output_File_Pointer,"                   ");
     elsif item3(1..item3_length) = "512MB DIMM" then
        Ada.Text_IO.put(Output_File_Pointer,"                         ");
     end if;
     item33 := INTEGER(Item_Number_3);
     Ada.Integer_Text_IO.put(Output_File_Pointer,item33);
     Ada.Text_IO.put(Output_File_Pointer,"                     ");

     if item3(1..item3_length) = "CD-RW" then
        Ada.Float_Text_IO.put(Output_File_Pointer,CDRW, fore => 4, aft =>2, exp => 0);
     elsif item3(1..item3_length) = "20 GB DDS" then
        Ada.Float_Text_IO.put(Output_File_Pointer,DDS, fore => 4, aft =>2, exp => 0);
     elsif item3(1..item3_length) = "128 MB USB Stick" then
        Ada.Float_Text_IO.put(Output_File_Pointer,usbstick, fore => 4, aft =>2, exp => 0);
     elsif item3(1..item3_length) = "512MB DIMM" then
        Ada.Float_Text_IO.put(Output_File_Pointer,DIMM, fore => 4, aft =>2, exp => 0);
     elsif item3(1..item3_length) = "Wireless Mouse" then
        Ada.Float_Text_IO.put(Output_File_Pointer,mouse, fore => 4, aft =>2, exp => 0);
     else Ada.Text_IO.put(" ");
     end if;

     Ada.Text_IO.put(Output_File_Pointer,"                    ");
     Ada.Float_Text_IO.put(Output_File_Pointer,sale3, fore => 4, aft =>2, exp => 0);
  end if;

  Ada.Text_IO.new_line(Output_File_Pointer);
  Ada.Text_IO.new_line(Output_File_Pointer);
  Ada.Text_IO.new_line(Output_File_Pointer);
  Ada.Text_IO.put (Output_File_Pointer,"                                                                        Total Sale: $");
  Ada.Float_Text_IO.put(Output_File_Pointer,totalsale, fore => 4, aft =>2, exp => 0);

  Ada.Text_IO.new_line(Output_File_Pointer);
  Ada.Text_IO.new_line(Output_File_Pointer);
  Ada.Text_IO.put (Output_File_Pointer,"                                                                   Sales Tax(6.5%): $");
  Ada.Float_Text_IO.put(Output_File_Pointer,salestax, fore => 4, aft =>2, exp => 0);

  Ada.Text_IO.new_line(Output_File_Pointer);
  Ada.Text_IO.new_line(Output_File_Pointer);
  Ada.Text_IO.put (Output_File_Pointer,"                                                             Shipping and Handling: $");
  Ada.Float_Text_IO.put(Output_File_Pointer,SH, fore => 4, aft =>2, exp => 0);

  Ada.Text_IO.new_line(Output_File_Pointer);
  Ada.Text_IO.new_line(Output_File_Pointer);
  Ada.Text_IO.put (Output_File_Pointer,"                                                                         Total Due: $");
  Ada.Float_Text_IO.put(Output_File_Pointer,total, fore => 4, aft =>2, exp => 0);

end billing;
