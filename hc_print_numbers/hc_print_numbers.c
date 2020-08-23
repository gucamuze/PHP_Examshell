#include <unistd.h>

void hc_print_numbers() {
        write(1, "0123456789", 10);
}