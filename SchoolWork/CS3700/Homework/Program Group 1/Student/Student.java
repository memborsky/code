/**
* This program will create an object called Me. It will then proceed to output the data
*
* @author (Matt Emborsky)
* @date Feb. 2, 2005
*/

   import java.io.*;
   import java.util.*;

    public class Student
   {
   
      // static private StringTokenizer stok;
      // static private BufferedReader ir = new BufferedReader(new InputStreamReader(System.in), 1);
      // static private BufferedReader sr = new BufferedReader(new InputStreamReader(System.in));
   
      private String Name; // Makes the string `Name`.            -PRIVATE-
      private int ID; // Makes the Integer `ID`.                  -PRIVATE-
      private int YOG; // Makes the Integer `YOG`.                -PRIVATE-
      private String Major; // Makes the String `Major`.          -PRIVATE-
      
   
       public Student(String name, int id, int yog, String major)
      /**
      * Constructor for the class Student.
      */
      {
      
         this.Name = name; // Defines an Attribute of the class Student being `Name`.
         this.ID = id; // Defines an Attribute of the class Student being `ID`.
         this.YOG = yog; // Defines an Attribute of the class Student being `YOG`.
         this.Major = major; // Defines an Attribute of the class Student being `Major`.
      
      } // End of Constructor.
   
   
   
       public static void print (Student me)
      {
      
         System.out.println("Name:                " + me.Name);
         System.out.println("ID:                  " + me.ID);
         System.out.println("Year of Graduation:  " + me.YOG);
         System.out.println("Major:               " + me.Major);
      
      }
   
   
   /**
   * Begin data read.
   **/
   
   
       public static int readInt ()
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
   
       public static String readString()
      {
      
         String stre = " ";
      
         try
         {
            BufferedReader input = new BufferedReader(new InputStreamReader(System.in));
            stre = input.readLine();
            return stre;
         }
         
             catch (IOException ex)
            {
               System.out.print(ex);
            }
      
         return stre;
      
      }
   
   /**
   * End data read.
   **/
   
       public static void main ( String[] args )
      /**
      * Main program.
      */
      {
      
      
         System.out.print("Please input your name - ");
         String name = readString();
         System.out.print("Please input your ID # - ");
         int id = readInt();
         System.out.print("Please input your Year of Graduation - ");
         int yog = readInt();
         System.out.print("Please input your Major - ");
         String major = readString();
         System.out.println();
      
      // Create object Me from the class Student.
         Student Me = new Student(name, id, yog, major);
         System.out.println("Object Me created sucecssfully!");
         System.out.println();
      
         Me.print(Me);
      
      } // End `public static void main`
   
   } // End `public class Student`