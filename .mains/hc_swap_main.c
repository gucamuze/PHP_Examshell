#include<unistd.h>

void    hc_swap();

int main() {
    int *a, *b;
    int c, d;

    c = 'a';
    d = 'b';
    a = &c;
    b = &d;

    write(1, a, 1);
    write(1, b, 1);

    hc_swap(a, b);

    write(1, a, 1);
    write(1, b, 1);

    c = 'k';
    d = 'l';

    write(1, a, 1);
    write(1, b, 1);

    hc_swap(a, b);

    write(1, a, 1);
    write(1, b, 1);

}
