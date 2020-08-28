#include <unistd.h>

void    hc_putstr(char *str);

int main()
{
    char str1[] = "J'aime les fruits au sirop\n";
    char str2[] = " PAS CHANGER ASSIETE POUR FROMAGE      ";
    char str3[] = " \n \n coucou zoreil \n";

    hc_putstr(str1);
    hc_putstr(str2);
    hc_putstr(str3);
}