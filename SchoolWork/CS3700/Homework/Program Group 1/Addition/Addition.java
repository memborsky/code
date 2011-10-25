/**
* This program will ask for three Integer values and add them together.
* Uses variable data reading.
*
* @author (Matt Emborsky)
* @date Feb. 2, 2005
*/

   import java.io.*;
   import java.util.*;

    public class Addition
   {

       public static void main(String[] args)
       /**
       * Main program
       */
      {

      /**
      * This section will retrive the data.
      **/
         System.out.print ("Please input your first Integer value - "); // Prompts for first value.
         int x = readInt(); // Reads in first value till `Enter` is pressed.
         System.out.print ("Please input your first Integer value - "); // Prompts for second value.
         int y = readInt(); // Reads in first value till `Enter` is pressed.
         System.out.print ("Please input your first Integer value - "); // Prompts for third value.
         int z = readInt(); // Reads in first value till `Enter` is pressed.


         int answer = adder(x, y, z); // Calls the

         System.out.println (x + " + " + y + " + " + z + " = " + answer);

      }

       public static int adder ( int x, int y, int z )
        /**
       * Take the three digits entered and add them together.
       */
      {

         int ans = x + y + z; // Adds the values together.
         return ans; // Returns the answer to the program.

      }


       public static int readInt ()
      /**
      * Parse input buffer to read the Integer value from the data inputed.
      **/
      {

         int inte = 0;

         try
         {
            BufferedReader input = new BufferedReader(new InputStreamReader(System.in));
            inte = Integer.parseInt(input.readLine());
         }

             catch (IOException ex)
            {
               System.out.print(ex);
            }

         return inte;

      }

   }
