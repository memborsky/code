With Ada.Strings.Unbounded;

-----
-- Label Table for assembler.
--
-- This will handle our label table features for the assembler we are writing.
--
-- @package   Labels
-- @author    Matt Emborsky <mlemborsky01@indianatech.net>
-----
Package Labels is

  -----
  -- Append a value to the label table.
  --
  -- This function will allow us to "append" a label to the label table.
  --
  -- @access public
  -----
  Procedure Append (Label : String; Value : Integer);

  -----
  -- Get the location of a label.
  --
  -- Retrieve the location of the label we pass in so that we can expand labels on the second pass.
  --
  -- @access public
  -----
  Function Get_Value (Label : String) Return Integer;

  -----
  -- Check to see if the lable is in the label table.
  --
  -- Search through the label table to see if the label is in the table or not.
  -- Return true if it is, else false.
  --
  -- @access public
  -----
  Function Is_In (Label : String) Return Boolean;

Private

  -----
  -- Pre-List Record definition.
  --
  -- Need to be defined before the full definition so that we can point back to the same type with
  -- our pointer type. This is to create our linked list.
  --
  -- @access private
  -----
  Type List;

  -----
  -- Pointer to our next List Record.
  --
  -- Used to point from our current list to the next list in the linked list.
  --
  -- @access private
  -----
  Type Pointer is Access List;

  -----
  -- Our List definition
  --
  -- This defines what data we are holding in our list. Currently we are only needing to
  -- maintain a lable and its location. The next points to the next list in the linked
  -- list, else it points to null.
  --
  -- @access private
  -----
  Type List is Record
    Label : Ada.Strings.Unbounded.Unbounded_String;
    Value : Integer;
    Next : Pointer;
  End Record;

End Labels;
