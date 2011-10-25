/**
*
* Random number generation program.
* Generates 100 values.
* Outputs them with only 2 decimal places.
*
**/

#include <iostream>
#include <iomanip>
#include <stdlib.h>
#include <time.h>

using namespace std;

float values [100]; /* Will hold all data for the program */
int i; /* Used for loop index */
int width; /* Used to properly format the data output */

int main () {
    
    /* Manipulation of the Floating point data output to the console/screen. */
    cout << setprecision (2);
    cout.setf(ios::fixed, ios::floatfield);
    cout.setf(ios::showpoint);

    /* Seed the random number generator */
    srand( (unsigned int) clock() );
    
    /* Input the random values into the array `values` */
    i = 0;
    while (i <= 99) {
          
          values[i] = (float) (rand() % 1000);
          i++;
          
    }
    
    /* Output data to screen via fixed size and precision of the floating point value */
    i = 0;
    width = 5;
    while (i <= 99) {
          
          if ( values[i] < 100 ) {
               width = 6;
          } 
          
          cout << ios::fixed << values[i] << setw(width);
          
          
          if ( (i + 1) % 10 == 0 ) {
               cout << endl << setw(-1 * width);
          }
          i++;
          width = 5;
    }
    
    /* Grrr */
    return 0;
}
