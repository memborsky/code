/*
* zombie: creates a zombie
*/

#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

int main(int argc, char **argv) {
  int pid;
  char cmd[255];

  if ((pid=fork()) == 0) {
    /* child */
    printf("child: my pid: %d, my parent: %d\n", getpid(), getppid());
    printf("child: exiting\n");
  } else {
    /* parent */
    printf("parent: my child: %d, my pid: %d, my parent: %d\n", pid, getpid(), getppid());
    printf("parent: sleeping for a second ...\n");

    sleep(1);

    sprintf(cmd, "ps -fp %d", pid);

    system(cmd);

    printf("parent: exiting\n");
  }

  return 0;
}
