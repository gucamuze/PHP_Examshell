#include <stdio.h>

void hc_swap(int *a, int *b);

// void hc_swap(int *a, int *b)
// {
//     int tmp;

//     tmp = *a;
//     *a = *b;
//     *b = tmp;
// }

int main()
{
    int a = 42;
    int b = 69;
    int *ptr = &a;
    int *ptr2 = &b;

    printf("%d\n", a);
    printf("%d\n", b);

    hc_swap(ptr, ptr2);

    printf("%d\n", a);
    printf("%d\n", b);

    a = 0;
    b = 1337;
    int *ptr3 = &a;
    int *ptr4 = &b;

    printf("%d\n", a);
    printf("%d\n", b);

    hc_swap(ptr3, ptr4);

    printf("%d\n", a);
    printf("%d\n", b);
}