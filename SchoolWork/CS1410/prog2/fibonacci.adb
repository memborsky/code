-- Fichero: fibonacci.adb 
-- Autor: Karmelo Uzelai - EUPT 
-- Descripcion: Mide tiempos en el cálculo del número de fibonacci 
--    de forma recursiva, iterativa y aplicando una fórmula 
-- Fecha: 19-7-2001 
WITH Ada.Text_Io; USE Ada.Text_Io;
WITH Ada.Integer_Text_Io; USE Ada.Integer_Text_Io;
WITH Ada.Calendar;

WITH Ada.long_float_Text_Io; USE Ada.long_float_Text_Io;

PROCEDURE Fibonacci IS 

   FUNCTION Fib_Cte (N:IN Integer) RETURN Integer IS 
      -- Recibe un número 
      -- Devuelve el número de Fibonacci, calculado por medio de una fórmula 
      Raiz5:CONSTANT := 2.2360679774997896964091736687312762354406183596115;
      -- mayor precisión que con: sqrt(5.0) (2.23607E+00)
   BEGIN 
      RETURN Integer( (((1.0+Raiz5)/2.0)**N - ((1.0-Raiz5)/2.0)**N ) / Raiz5);
   END Fib_Cte;

   FUNCTION Fib_Iter (N : IN Integer) RETURN Integer IS 
      -- Recibe un número 
      -- Devuelve el número de Fibonacci, calculado de forma iterativa 
      N2, F1, F2, F3 : Integer;
   BEGIN 
      N2:=2;
      F1:=1;
      F2:=1;
      WHILE N>N2 LOOP 
         F3 := F1+F2;
         F1:=F2;
         F2:=F3;
         N2:=N2+1;
      END LOOP;
      RETURN F2;
   END Fib_Iter;

   FUNCTION Fib_Rec (N:IN Integer) RETURN Integer IS 
      -- Recibe un número 
      -- "Intenta" devolver el número de Fibonacci, calculado recursivamente 
   BEGIN 
      IF N < 3 THEN RETURN 1;
      ELSE RETURN Fib_Rec(N-1)+Fib_Rec(N-2);   --Fib(n) = Fib(n-1)+Fib(n-2)
      END IF;
   END Fib_Rec;

   N : Integer;
   Hora1, Hora2, Hora : Integer;
   Dia1, Dia2 : Integer;

BEGIN 
   Put ("Número de fibonacci (con números mayores de 46, solución erronea por desbordamiento): ");
   Get (N);
   -- Cálculo por medio de una fórmula
   Hora1 := Integer(Ada.Calendar.Seconds (Ada.Calendar.Clock));
   Put("ca'lculo constante: "); Put(Fib_Cte(N),1);
   Hora2:= Integer(Ada.Calendar.Seconds (Ada.Calendar.Clock));
   Put (Hora2-Hora1); Put_Line (" segundos");
   -- Cálculo de forma iterativa
   Hora1:=Hora2;
   Put("ca'lculo iterativo: "); Put(Fib_Iter(N),1);
   Hora2:= Integer(Ada.Calendar.Seconds (Ada.Calendar.Clock));
   Put (Hora2-Hora1); Put_Line (" segundos");
   -- Cálculo de forma recursiva
   Hora1:=Hora2;
   Dia1:=Integer(Ada.Calendar.Day(Ada.Calendar.Clock));
   Put("ca'lculo recursivo: "); Put (Fib_Rec(N),1);
   Hora2 := Integer(Ada.Calendar.Seconds (Ada.Calendar.Clock));
   Dia2:=Integer(Ada.Calendar.Day(Ada.Calendar.Clock));
   Hora:=(Dia2-Dia1)*24*60*60+(Hora2-Hora1);
   Put (Hora/(24*60*60)); Put (" di'as, ");   -- Cálculo de días, horas, minutos y sg.
   Put ((Hora rem (24*60*60))/(60*60),1); Put (" h., ");
   Put ((Hora rem (60*60))/(60),1); Put (" m. ");
   Put (Hora rem 60,1); Put (" s.");
   Put (Character'Val (7));  --beep 
   New_Line;

END Fibonacci;
