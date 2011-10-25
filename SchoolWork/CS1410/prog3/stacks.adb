PACKAGE BODY STACKS IS

Function Full(S: Stack) Return Boolean IS
  
  Begin
	return S.Top = Size'last;
    End Full;

Function Empty(S: Stack) Return Boolean IS
    
  Begin
    Return (s.Empty);
  End Empty;
	 
  PROCEDURE PUSH(Stk: IN OUT Stack; Item: IN Element_Type)Is

    BEGIN
      IF Full(Stk) THEN
        RAISE Stack_Overflow;
      END IF;  
    
      If Empty(Stk) Then
	Stk.Top := Size'First;
	Stk.Empty := False;
      Else
        Stk.Top := Size'Succ(Stk.Top);
      End If;

      Stk.Data(Stk.Top) := Item;
    END PUSH;

  PROCEDURE POP(Stk: IN OUT Stack; Item: IN OUT Element_Type)Is
  
    BEGIN
      IF Empty(Stk) THEN
        RAISE Stack_Underflow;
      END IF;

      Item := Stk.Data(Stk.Top);

      IF Stk.Top = Size'First Then
	Stk.Empty := True;
      Else
	 Stk.Top := Size'Pred(Stk.Top);
      End If;
    END POP;
  
  Procedure Initialize(Stk: In Out Stack) Is
  
    Begin
      Stk.Empty := True;
      Stk.Top := Size'First;
    End Initialize;

  Function Peek(S: Stack) Return Element_Type IS
  
    Begin
      Return S.Data(S.Top);
    End Peek;
    
END STACKS;
