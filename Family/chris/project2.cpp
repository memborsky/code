#include <mpi.h>
#include <iostream.h>
#include <math.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
extern "C"
{
	#include <cblas.h>
	#include <clapack.h>
}

typedef double (* DMultiVarFunc)(double *x);


//headers
int feasible(double *x, double *a, double *b, int n);
void randompoint(double *x, double *a, double *b, int n);
double urand( );
void valuegradhessian(DMultiVarFunc f, double *x, double *dx, double *y, double *g, double *H, int n );
void MultiNewton(double *x, int n);
double f(double *x);

//main program
int main(int argc,char *argv[])
{
	int numprocs, numslaves, myrank, tag;
	MPI_Status status;

	int i;
	int master = 0;
	int nodenum = 1;
	int n =2;
	int numinits = 10;
	int seed = 0;
	double *x, *a, *A, *b, *B;
	x = new double[n];
	a = new double[n];
	A = new double[n];
	b = new double[n];
	B = new double[n];
	tag = 1;

	MPI_Init(&argc,&argv);
	MPI_Comm_size( MPI_COMM_WORLD, &numprocs );
	MPI_Comm_rank( MPI_COMM_WORLD, &myrank );
	numslaves = numprocs - 1;

	for (i = 0; i < n; i++)
	{
		a[i] = -1;
		b[i] = 1;
		A[i] = -2;		
		B[i] = 2;
	}

	if ( myrank == master )
	{
		srand( seed );
		for (i = 0; i < numinits; i++)
		{
			randompoint(x, A, B, n);
			if ( feasible(x, a, b, n) )
			{
				if (nodenum > numslaves)
				{
					nodenum = 1;
				}
				MPI_Send(&x,n,MPI_DOUBLE,nodenum,tag,MPI_COMM_WORLD);
				MPI_Recv(&x,n,MPI_DOUBLE,nodenum,tag,MPI_COMM_WORLD,&status);
				nodenum++;
			}
		}
	}
	else
	{
		MPI_Recv(&x,n,MPI_DOUBLE,master,tag,MPI_COMM_WORLD,&status);
		MultiNewton(x,n);
		MPI_Send(&x,n,MPI_DOUBLE,master,tag,MPI_COMM_WORLD);
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

void MultiNewton( double *x, int n);
{
	int i,j, k, pivs;
	int test = 0;
	pivs = new int[n];
	int NewtonBailout = 50;
	double *y, *dx, *g, *H;
	double eps = 0.01;
	double Newtontoler = 0.00001;
	dx = new double[n];
	y  = new double[1];
	g  = new double[n];
	H  = new double[n*n];

	for( j = 0; j < n; j++ )
	{
		dx[j] = eps;
	}
	for( k = 0; k < NewtonBailout; k++)
	{
		valuegradhessian(f,x,dx,y,g,H,n);
		clapack_dgesv(CblasColMajor,n,1,H,n,pivs,g,n);
		for( i = 0; i < n; i++)
		{
			if( g[i] >= Newtontoler )
			{
				test += 1;
			}
		}
		if( test < n)
		{
			cblas_daxpy(n,-1,g,1,x,1);
		}
		test = 0;
	}
	return (void);
}

void valuegradhessian(DMultiVarFunc f, double *x,double *dx,double *y, double *g, double *H, int n )
{
	double *xp,*xm,*xpp,*xpm,*xmp,*xmm,yp,ym,ypp,ypm,ymp,ymm;
	int i,j,k;
	xp = new double[n];
	xm = new double[n];
	xm = new double[n];
	xpp = new double[n];
	xpm = new double[n];
	xmp = new double[n];
	xmm = new double[n];
	y[0] = f(x);
	for( i = 0; i < n; i++ )
	{
		for ( j=0; j < n;j++)
		{
			xp[j] = xm[j] = x[j];
		}
		xp[i] += dx[i];
		xm[i] -= dx[i];
		yp = f(xp);
		ym = f(xm);
		g[i] = ( yp - ym ) / ( 2 * dx[i] );
		H[i*n+i] = ( yp - 2 * y[0] + ym ) / ( dx[i] * dx[i] );

		for( k = 0; k < n; k++ )
		{
			for( j = 0; j < n; j++ )
			{
				xpp[j] = xpm[j] = xmp[j] =xmm[j]=x[j];
			}
			xpp[i] += dx[i];
			xpp[k] += dx[k];
			xpm[i] += dx[i];
			xpm[k] -= dx[k];
			xmp[i] -= dx[i];
			xmp[k] += dx[k];
			xmm[i] -= dx[i];
			xmm[k] -= dx[k];

			ypp = f(xpp);
			ypm = f(xpm);
			ymp = f(xmp);
			ymm = f(xmm);
			H[i*n+k] = H[k*n+i] = ( ypp - ypm - ymp + ymm ) / ( 4 * dx[i] * dx[k] );
		}
	}
	delete xp , xm , xpp , xpm , xmp , xmm;
	return (void);
}

double f(double *x)
{
	return( x[0] * x[0] + x[1] * x[1] - 3 * x[0] * x[1] );
}