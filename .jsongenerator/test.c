#include <unistd.h>
#include <stdio.h>

void hc_putstr(char* str)
{
    while(*str)
        write(1, str++, 1);
    write(1, "\n", 1);
}

int main(int argc, char** argv)
{
    if (argc > 1)
        hc_putstr(argv[argc-1]);
}
