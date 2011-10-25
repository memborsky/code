;This is an example program
  zero:   .WORD   0, 1, -1
				.BLOCK  10
        .PROG   example
label:  .EQU    error
strt:   ADD     zero
SysLabel0: .BLOCK 10
 ADD #0
 LDA 
 ADD 
 STA SysLabel0
        STA			zero + 4
        NOP   ;This is a comment
        STA   zero + 2 ;this is another comment
SysLabel1: .BLOCK 10
 LDA zero+4
 ADD zero
 STA SysLabel1
done:   .END     example
