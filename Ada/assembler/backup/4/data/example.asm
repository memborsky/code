;This is an example program
  zero:   .WORD   0, 1, -1
				.BLOCK  10
        .PROG   example
strt:   ADD     zero
        STA			zero + 4
done:   END     example
