#include <unistd.h>
#include <stdio.h>

char    *hc_strcpy(char *s1, char *s2);

// char    *hc_strcpy(char *s1, char *s2)
// {
//     int n = 0;

//     while(s2[n])
//     {
//         s1[n] = s2[n];
//         n++;
//     }
//     s1[n] = '\0';

//     return s1;
// }

int main()
{
    char s1[100];
    char s2[] = "          TESTING STRCPY PLEASE IGNORE    8^###hz€f\t";
    char s3[] = "Il est déjà plus de minuit je devrais aller dormir";

    hc_strcpy(s1, s2);
    hc_strcpy(s2, s3);

    puts(s1);
    puts(s2);
}