#include <stdio.h>

int main() {
    if (1) {
        if (2) {
            if (3) {
                if (4) {
                    if (5) {
                        printf("Too many nested brackets!\n");
                    }
                }
            }
        }
    }
    return 0;
} 