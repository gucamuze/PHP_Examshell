int hc_strlen(char *str)
{
    int i;

    i = 0;

    while (str[i])
        i++;
    return i;
}

char *hc_strrev(char *str)
{
    int i;
    int strlen;
    char tmp;

    i = 0;
    strlen = hc_strlen(str);
    while (i < strlen / 2)
    {
        tmp = str[i];
        str[i] = str[strlen - 1 - i];
        str[strlen - 1 - i] = tmp;
        i++;
    }
    return str;
}