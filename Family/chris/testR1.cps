#include <stdio.h>
#include <math.h>
#include <stdlib.h>
#include <memory.h>
#include <rosenbrock.h>

#define SQR(a) ((a)*(a))

void obj32(int nparam,double *x,double *fj,void *extraparams)
{
   *fj=pow((x[0]-2.0),4.0)+pow((x[0]-2.0*x[1]),2.e0);
//    *fj=100*SQR(x[1]-SQR(x[0]))+SQR(1-x[0]);
   return;
}

void message(int n,double *x)
{
    double y;
    int i;
    printf("optimum found at:\n");
    for (i=0; i<n; i++)
        printf(" x[%i]=%f\n",i+1,x[i]);
    obj32(n,x,&y,NULL);
    printf("objective function value= %f\n", y);
};

int main()
{
	int nparam,maxIter,verbosity;
	double *x,*bl,*bu,bigbnd,eps;

	nparam=2;
	x=(double*)calloc(nparam,sizeof(double));
	bl=(double *)calloc(nparam,sizeof(double));
    bu=(double *)calloc(nparam,sizeof(double));
	bigbnd=1.e10;
	maxIter=5000;
    eps=1.e-5;
	verbosity=1;

	bl[0]=bl[1]=-10;
    bu[0]=bu[1]=10;

    x[0]=5;
    x[1]=5;

	rosenbrock(nparam,x,bl,bu,bigbnd,maxIter,eps,verbosity,obj32,NULL);

    message(nparam,x);

	free(x);
	free(bl);
	free(bu);
	return 0;
}