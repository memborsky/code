/**
* Create's a 2-d, 4 x 4, array of Integers.
* It includes the java.util.* so we can utilize the Random class.
*
* @author (Matt Emborsky)
* @date Feb. 2, 2005
*/

   import java.util.*; // Import's random function

    public class Array
   {



       public void populate (int arr[][])
      /**
      * Populate some data into the Integer array.
      **/
      {

         Random rand = new Random();
      /**
      * Instantiate the Random class into the program
      * where the object name is `rand` and all functions of Random are preceeded by
      * rand.xxxxxxx
      **/

      // Input data into IntArray.
         for (int i = 0; i < arr.length; i++) { // Allows for column circulation of the array

            for (int j = 0; j < arr.length; j++) { // Allows for row circulation of the array

               arr[i][j] = rand.nextInt(9999); // Generate a number between 0-9,999

            } // End for j, rows.

         } // End for i, columns.

      } // End `public void populate`




       public void print (int arr[][]) {
         // Print data to screen from IntArray.

         for (int i = 0; i < arr.length; i++) { // Parses through the columns in the array

            for (int j = 0; j < arr.length; j++) { // Parses through the rows in the array

               if (arr[i][j] <= 10) {
               /**
               * If we are lucky enough to get a number below 10 then we will do this section
               * which formats the data so that we can have 4 spaces before the number.
               **/

                  System.out.print("   " + arr[i][j] + " ");
               }
               else if (arr[i][j] < 100) {
               /**
               * If the value in the current feild of the array is less than 99 than we will
               * do this section of code so that we get three spaces before the number.
               **/

                  System.out.print("  " + arr[i][j] + " ");
               }
               else if (arr[i][j] < 1000) {
               /**
               * If the value in the current feild of the array is less than 1,000 than we will
               * do this section of code so that we get two spaces before the number.
               **/

                  System.out.print(" " + arr[i][j] + " ");
               }
               else {
               /**
               * If the value in the current feild of the array is 1,000+ we will do this section of code
               * so that we don't get that extra space for output manipulation.
               **/

                  System.out.print(arr[i][j] + " ");
               } // End if for data manipulation.

            } // End for j, rows.

            System.out.println();

         } // End for i, columns.

      } // End `public void print`




       public static void main(String [] args)
      /**
      * Main Program.
      **/
      {

         int[][] IntArray = new int[4][4]; // Create IntArray as a 4 x 4 Integer array.

         Array Arrays = new Array();

         Arrays.populate(IntArray);
         Arrays.print(IntArray);

      } // End of `public void static main`

   } // End of `public class Array`
