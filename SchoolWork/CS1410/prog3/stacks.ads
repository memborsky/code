Generic 

  Type Size Is (<>);
  Type Element_Type Is Private;

Package STACKS Is

  Type Stack Is Private;
  
  Procedure Push(Stk: In Out Stack; Item: In Element_Type);

  Procedure Pop(Stk: In Out Stack; Item: In Out Element_Type);
  
  Procedure Initialize(Stk: In Out Stack);
  
  Function Empty(S: IN Stack) Return Boolean;
  
  Function Peek(S: IN Stack) Return Element_Type;

  Stack_Overflow, Stack_Underflow: EXCEPTION;

  Private
    Type Stkdata Is Array(Size) OF Element_Type;
    Type Stack Is Record
      Data : Stkdata;
      Top : Size;
      Empty: Boolean := True;
    End Record;

End STACKS;
