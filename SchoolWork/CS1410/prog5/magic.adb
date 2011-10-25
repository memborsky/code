   WITH Ada.Text_IO, Ada.Integer_Text_IO;
   Use Ada;


--****************************************************************************--
--* Magic Squares                                                            *--
--*                                                                          *--
--*  This program will calculate any magic square of any size (even or odd)  *--
--*  and output the square to the screen.                                    *--
--*                                                                          *--
--* By Matt Emborsky; November 17, 2004                                      *--
--****************************************************************************--

    Procedure magic IS
   
   -- Type decleration
      Type square IS Array (Integer Range <>, Integer Range <>) OF Integer;
   
   -- Variable decleration
      dimension : Integer;
      ending_program : Boolean := False;
      
       Procedure get_data (n : IN OUT Integer) IS
      
      Begin
      
         Text_IO.put("Please input the dimension - ");
         Integer_Text_IO.get(n);
         IF n = 0 Then
            ending_program := True;
         End IF;
         
      End get_data;
   
   
       Procedure run(n : IN Integer) IS
      
         s : square(1..1000,1..1000);
      
      -- Defining extra procedures to do squares.
          Procedure odd_square (s : IN OUT square; n : IN OUT Integer);
          Procedure s_even_square (s : IN OUT square; n : IN OUT Integer);
          Procedure d_even_square (s : IN OUT square; n : IN OUT Integer);
      
          Procedure put_data(s : IN OUT square; n : IN OUT Integer) IS
         
         Begin
         
            For i IN 0..n-1 Loop
               For j IN 0..n-1 Loop
                  IF s(i,j) >= 100 Then
                     Integer_Text_IO.put(s(i,j), 1);
                     Text_IO.put(" ");
                  Elsif s(i,j) >= 10 Then
                     Integer_Text_IO.put(s(i,j), 1);
                     Text_IO.put("  ");
                  Else
                     Integer_Text_IO.put(s(i,j), 1);
                     Text_IO.put("   ");
                  End IF;
               End Loop;
               Text_IO.new_line;
            End Loop;
         
         End put_data;
      
      
      
          Procedure magic_square(s : IN OUT Square; n : IN OUT Integer) IS
         
         Begin
         
            IF n MOD 2 = 1 Then
               odd_square(s, n);
            Else
               IF n MOD 4 = 0 Then
                  d_even_square(s, n);
               Else
                  s_even_square(s, n);
               End IF;
            End IF;
            put_data(s, n);
         
         End magic_square;
      
      
      	
          Procedure odd_square(s : IN OUT square; n : IN OUT Integer) IS
         
            nsqr : Integer := n*n;
            i : Integer := 1;
            j : Integer := n/2; -- Start Position
            k_i : integer;
         
         Begin
         
            k_i := 1;
            For k IN 1..nsqr Loop
               s(i,j) := k_i;
               i := i - 1;
               j := j + 1;
               IF k_i MOD n = 0 Then
                  i := i + 2;
                  j := j - 1;
               Else
                  IF j = n Then
                     j := j - n;
                  Elsif i < 0 Then
                     i := i + n;
                  End IF;
               End IF;
               k_i := k_i + 1;
            End Loop;
         
         End odd_square;
      
      
          Procedure d_even_square (s : IN OUT square; n : IN OUT Integer) IS
         
            Type inside_array IS Array (Integer Range <>, Integer Range <>) OF Integer;
            I_a : inside_array(1..n, 1..n);
            J_a : inside_array(1..n, 1..n);
            index : Integer;
         
         Begin
         
            index := 1;
            For i IN 1..n Loop
               For j IN 1..n Loop
                  I_a(i,j) := ((i+1) MOD 4) / 2;
                  J_a(i,j) := ((i+1) MOD 4) / 2;
                  s(i,j) := index;
                  index := index + 1;
               End Loop;
            End Loop;
         
            For i IN 1..n Loop
               For j IN 1..n Loop
                  IF I_a(i,j) = J_a(i,j) Then
                     s(i,j) := (((n * n) + 1) - s(i,j));
                  End IF;
               End Loop;
            End Loop;
         
         End d_even_square;
      
      
          Procedure s_even_square (s : IN OUT square; n : IN OUT Integer) IS
         
            Type inside_array IS Array (Integer Range <>) OF Integer;
            p : Integer := n/2;
            temp : Integer;
            i,j,c : Integer;
            M : square(1..p,1..p);
            I_a : inside_array(1..p);
            J_a : inside_array(1..1000);
            k : integer;
         
         Begin
         
            magic_square(M, p);
         
            For i IN 1..p-1 Loop
               For j IN 1..p-1 Loop
               
                  s(i,j) := M(i,j);
                  s(i+p,j) := M(i,j) + (3 * p * p);
                  s(i,j+p) := M(i,j) + (2 * p * p);
                  s(i+p,j+p) := M(i,j) + (p * p);
               
               End Loop;
            End Loop;
         
            IF n /= 2 Then
            
               For i IN 1..p-1 Loop
               
                  I_a(i) := i + 1;
               
               End Loop;
            
               k := (n-2) / 4;
               c := 1;
               For i IN 1..k Loop
               
                  J_a(c) := i;
                  c := c + 1;
               
               End Loop;
            
               For i IN (n-k+2)..n Loop
               
                  J_a(c) := i;
                  c := c + 1;
               
               End Loop;
            
               c := c - 1;
               For i IN 1..p Loop
                  For j IN 1..c Loop
                  
                     temp := s(i-1,J_a(j-1)-1);
                     s(i-1,J_a(j-1)-1) := s(i+p-1,J_a(j-1)-1);
                     s(i+p-1,J_a(j-1)-1) := temp;
                  
                  End Loop;
               End Loop;
            
               i := k;
               j := 0;
               temp := s(i,j);
               s(i,j) := s(i+p,j);
               s(i+p,j) := temp;
            
               j := i;
               temp := s(i+p,j);
               s(i+p,j) := s(i,j);
               s(i,j) := temp;
            
            End IF;
         
         End s_even_square;
      
      Begin
      
         IF Not ending_program Then
            magic_square(s, dimension);
            magic;
         End IF;
      
      End run;
   
   
   Begin
   
      get_data(dimension);
      run(dimension);
   
   End magic;