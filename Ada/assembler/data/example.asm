;This is an example program
  zero:   .WORD   0, 1, -1
				.BLOCK  10
        .PROG   example
label:  .EQU    error
mymacro: .MACRO THIS,PARAM,%LABELS
LABELS: .BLOCK  10
        .IF     .EMPTY.PARAM
        ADD     #0
        .ENDIF
        .IF     THIS .LS. PARAM
        LDA     THIS
        .ENDIF
        .IF     THIS .GT. PARAM
        LDA     PARAM
        .ENDIF
        ADD     PARAM
        STA     LABELS
        .ENDM   mymacro
strt:   ADD     zero
        mymacro zero,,
        STA			zero + 4
        NOP   ;This is a comment
        STA   zero + 2 ;this is another comment
        mymacro zero + 4,zero
done:   .END     example
