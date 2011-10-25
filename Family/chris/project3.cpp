#include <mpi.h>
#include <iostream.h>
#include <string.h>
#include <math.h>
#include <stdlib.h>

//headers
int feasible(double *x, double *a, double *b, int n);
void randompoint(double *x, double *a, double *b, int n);
double urand( );

//main program
int main(int argc,char *argv[])
{
	int numprocs, numslaves, myrank;
	double compute = 1;
	MPI_Status status;

	int i,j,numinits;
	int master = 0;
	int numfeez = 0;
	int nodenum = 1;
	int n =2;
	int seed = 0;
	double *x, *a, *A, *b, *B;
	x = new double[n];
	a = new double[n];
	A = new double[n];
	b = new double[n];
	B = new double[n];

	MPI_Init(&argc,&argv);
	MPI_Comm_size( MPI_COMM_WORLD, &numprocs );
	MPI_Comm_rank( MPI_COMM_WORLD, &myrank );
	numslaves = numprocs - 1;
	numinits = 3 * numslaves;

	if ( myrank == master )
	{
		for (i = 0; i < n; i++)
		{
			a[i] = -1;
			b[i] = 1;
			A[i] = -2;		
			B[i] = 2;
		}
	
		srand( seed );
		while (numfeez < numinits)
		{
			randompoint(x, A, B, n);
			if ( feasible(x, a, b, n) )
			{
				if (nodenum > numslaves)
				{
					nodenum = 1;
				}
				MPI_Send(&x,n,MPI_DOUBLE,nodenum,compute,MPI_COMM_WORLD);
				numfeez++;
				nodenum++;
			}
		}
	}
	else
	{
		for ( i = 0; i < numinits/numslaves; i++)
		{
			MPI_Recv(&x,n,MPI_DOUBLE,master,compute,MPI_COMM_WORLD,&status);
			cout << "Function "<<i<<" : "<<x[0]*x[0]+x[1]*x[1]<<endl;
		}
	}
	MPI_Finalize();
	return 0;
}

double urand()
{
	double y;
	y = (double)(rand());
	return (y/RAND_MAX);
}

void randompoint(double *x, double *a, double *b, int n)
{
	int i;
	for (i = 0; i < n; i++)
	{
		x[i] = a[i] + ( b[i] - a[i] ) * urand();
	}
}

int feasible(double *x, double *a, double *b, int n)
{
	int i = 0;
	int feez = 1;
	while (feez && (i < n) )
	{
		feez = feez && (a[i] <= x[i]) && (x[i] <= b[i]);
		i += 1;
	}
	return (feez);
}
