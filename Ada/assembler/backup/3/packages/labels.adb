-----
-- Label Table for assembler.
--
-- This will handle our label table features for the assembler we are writing.
--
-- @package   Labels
-- @author    Matt Emborsky <mlemborsky01@indianatech.net>
-----
Package Body Labels is

  -----
  -- Holds the head pointer of our list of labels.
  --
  -- @access  Private
  -- @var     Pointer (or Node of the Linked List)
  -----
  Head : Pointer := Null;

  -----
  -- Append a value to the label table.
  --
  -- This function will allow us to "append" a label to the label table.
  --
  -- @access public
  -----
  Procedure Append (Label : String; Value : Integer) is

    New_Pointer : Pointer := Null;

  Begin

    New_Pointer := New List'(Ada.Strings.Unbounded.To_Unbounded_String(Label), Value, Head);

    Head := New_Pointer;

  End Append;


  -----
  -- Get the location of a label.
  --
  -- Retrieve the location of the label we pass in so that we can expand labels on the second pass.
  --
  -- @access public
  -----
  Function Get_Value (Label : String) Return Integer is

    Scan : Pointer := Head;

  Begin

    Loop

      If Ada.Strings.Unbounded.To_String(Scan.Label) = Label Then
        Return Scan.Value;
      End If;

      Exit When Scan.Next = Null;
      Scan := Scan.Next;

    End Loop;

    Return -1;

  End Get_Value;

  -----
  -- Check to see if the lable is in the label table.
  --
  -- Search through the label table to see if the label is in the table or not.
  -- Return true if it is, else false.
  --
  -- @access public
  -----
  Function Is_In (Label : String) Return Boolean is

    Scan : Pointer := Head;

  Begin

    If Scan /= Null Then

      Loop

        If Ada.Strings.Unbounded.To_String(Scan.Label) = Label Then
          Return True;
        End if;

        Exit When Scan.Next = Null;
        Scan := Scan.Next;

      End Loop;

    End If;

    Return False;

  End Is_In;

End Labels;
