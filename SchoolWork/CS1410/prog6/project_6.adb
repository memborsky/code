   With Ada.Text_IO, Ada.Integer_Text_IO;
   Use Ada;

    Procedure project_6 IS

      Type node;
      Type pointer IS Access node;
      Type node IS Record
            data : string(1..20);
            left : pointer;
            right : pointer;
         End Record;

      ending_game : Boolean;
      tree : node;

   -----------------------------------------------------

       Procedure clear_screen IS

      Begin

         Text_IO.put(ASCII.ESC & "[2J");
         Text_IO.flush;
         Text_IO.put(ASCII.ESC & "[0;0f");
      	Text_IO.flush;

      End clear_screen;

   -----------------------------------------------------

       Procedure get_data(answer : IN OUT string)  IS

      Begin

         Text_IO.get(answer);

      End get_data;


   -----------------------------------------------------

       Procedure init (T : IN OUT node) IS

      Begin

         T.left := null;
         T.right := null;
         T.data := "Does the animal live in water?";
         T.left.data := "Is it a whale?";
         T.right.data := "Is it a cow?";

      End init;

   -----------------------------------------------------
   Begin

      While NOT ending_game Loop
         clear_screen;
         init(tree);
      End Loop;

   End project_6;
