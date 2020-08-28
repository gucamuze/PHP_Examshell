#include <stdio.h>

int hc_strlen(char *str);

// int hc_strlen(char *str)
// {
//     int c = 0;

//     while (*str)
//     {
//         c++;
//         str++;
//     }

//     return c;
// }

int main()
{
    char str1[] = "ezfbbfuzmugbzmrgnjzmrg";
    char str2[] = "                  ";
    char str3[] = "";

    printf("%d\n", hc_strlen(str1));
    printf("%d\n", hc_strlen(str2));
    printf("%d\n", hc_strlen(str3));
}