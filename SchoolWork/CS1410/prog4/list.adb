With Ada.Text_IO;

Package Body List IS

	Function empty (ptr : list) Return Boolean IS

	Begin

		If ptr.next = null Then
			Return True;
		Else
			Return False;
		End If;

	End empty;


	Procedure create_a_node (ptr : IN OUT list) IS

	Begin

		ptr := New node;

	End create_a_node;

	Procedure init (ptr : IN OUT list);

	Begin

		ptr.next := null;

	End init;

	Procedure insert (start : IN OUT list; item_1 : element; item_2);

	Begin

		If empty Then
			create_a_node(start);
			start.next := null;
			start.first := item_1;
			start.last := item_2;
		Else
			create_a_node(start);
			start.next := start;
			start.next.first := item_1;
			start.next.last := item_2;
		End If;

	End insert;

	Procedure print (start : IN OUT list);

	Begin

		
End List;