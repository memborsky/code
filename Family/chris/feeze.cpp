// testing feasible points 

#include <iostream.h>
#include <stdlib.h>
#include <string.h>

// headers
int feasible(double *x, double *a, double *b, int n);
void randompoint(double *x, double *a, double *b, int n);
double urand( ); 


// main program
int main(int argc,char**argv)
{int i;
 int n = 2;
 int numits = 100; 
 int seed = 0;
 double *x, *a, *b, *A, *B;
 x = new double[n];
 a = new double[n];
 b = new double[n];
 A = new double[n];
 B = new double[n];
  
 for (i=0; i < n; i++) 
 {
   a[i] = -1;
   A[i] = -2;
   b[i] = 1;
   B[i] = 2;
 }  
 
 srand(seed);
 for (i=0; i < numits; i++) 
 { randompoint(x,A,B,n);
   if(feasible(x,a,b,n))
   { 
	   cout << "[" << x[1] << ", "<<x[2] << "]" << " feasible " << endl; 
   }  
   else
   { 
     cout << "point #" << i << " infeasible " << endl; 
   }
 }        
}   

double urand( )
{double y;
 y = (double) (rand());
 return (y/RAND_MAX);
} 

void randompoint(double *x, double *a, double *b, int n) 
{int i;
  for (i=0; i < n; i++) x[i] = a[i] +(b[i]-a[i])*urand();
}      

int feasible(double *x, double *a, double *b, int n) 
{int i,feeze;
  feeze = 1;
  i = 0;
  while (feeze && (i <n) )
  {
   feeze = feeze && (a[i] <= x[i]) && (x[i] <= b[i]);
   i += 1; 
  }
  return(feeze);
}        
   
