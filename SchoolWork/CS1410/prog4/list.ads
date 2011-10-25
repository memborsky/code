Generic

    Type element IS Private;

Package List IS

    Type List IS Limited Private
    Procedure init (ptr : IN OUT list);
    Procedure clear (ptr : IN OUT list);
    Procedure copy (old : IN OUT list; nel : IN OUT list);
    Function search (ptr : list; item : element; item_length : integer) Return Boolean;
    Procedure insert (start : IN OUT list; item_1, item_2 : element);
    Procedure delete (start : IN OUT list; item_1, item_2 : element);
    Procedure print (start : IN OUT list);
    
    Private
    
	Type Node;
	Type List IS Access Node;
	Type Node is Record
	    first : element;
	    last : element;
	    next : List;
	  End Record;

End List;