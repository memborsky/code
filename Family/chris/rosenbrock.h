/******************************************************************/
/*  Rosenbrock - Header file to be included in user's main        */
/*               program.                                         */
/******************************************************************/

#ifndef __INCLUDE__ROSEN_H___
#define __INCLUDE__ROSEN_H___

void rosenbrock(int n, double *x, double *bl, double *bu,
				double bigbnd, int maxiter, double eps, int verbose,
				void obj(int,double *,double *,void *), void *extraparams);

#endif