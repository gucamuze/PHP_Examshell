#include<stdio.h>

char    *hc_strrev(char *str);

int main() {
    char str1[100] = "Reverse these mofo";
    char str2[100] = " /// apifnalefbaf /*.. ";
    printf("%s\n", str1);
    printf("%s\n", hc_strrev(str1));
    printf("%s\n", str2);
    printf("%s", hc_strrev(str2));
}