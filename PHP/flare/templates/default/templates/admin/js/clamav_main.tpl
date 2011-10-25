{literal}

<script src="extensions/ClamAV/js/dhtmlXCommon.js"></script>
<script src="extensions/ClamAV/js/dhtmlXTree.js"></script>
<script type='text/javascript'>
    /**
    * Treeview code taken from GPL standard version
    * of dhtmlxTree. Code modified for inclusion with
    * Flare by Tim Rupp
    *
    * dhtmlxTree can be gotten from the below link
    * http://www.scbr.com/docs/products/dhtmlxTree/
    */
    //tree object
    var tree;

    //xml loader to load details from database
    var xmlLoader = new dtmlXMLLoaderObject(window);

    //id for new (unsaved) item
    var newItemId = "-1";

    //load tree on page
    function loadTree(data){
        tree = new dhtmlXTreeObject("treebox","100%","100%",0);
        tree.setImagePath("extensions/ClamAV/images/");
        tree.setOnClickHandler(doOnSelect);
        tree.loadXMLString(data);
    }

    //what to do when item selected
    function doOnSelect(itemId){
        if(itemId!=newItemId){
            tree.selectItem(newItemId,false)
            addEvent(itemId);
        }else{
            //set color to new item label
            tree.setItemColor(itemId,"red","pink")
            addEvent(itemId);
        }
    }

    /**
    * Add and remove event javascript taken from the site
    * shown below
    * http://www.dustindiaz.com/add-and-remove-html-elements-dynamically-with-javascript/
    */
    function addEvent(item) {
        var ni = document.getElementById('filelist');
        var clam = document.getElementById('clamform');
        var numi = document.getElementById('theValue');
        var num = (document.getElementById("theValue").value -1)+ 2;
        numi.value = num;
        var divIdName = "file"+num+"Div";
        var inputIdName = "item"+num;

        var newinput = document.createElement('input');
        newinput.setAttribute('type','hidden');
        newinput.setAttribute('name',"item["+num+"]");
        newinput.setAttribute('id',inputIdName);
        newinput.setAttribute('value',item);

        var newdiv = document.createElement('div');
        newdiv.setAttribute("id",divIdName);

        new_content = "<table width='100%'><tr><td style='width: 10%; text-align: center;'>"
        new_content = new_content + "<img src='extensions/ClamAV/images/delete.png' onClick=\"removeEvent('"+divIdName+"','"+inputIdName+"')\">";
        new_content = new_content + "</td><td width='90%'>"+item+"</td></tr></table>";
        newdiv.innerHTML = new_content;

        ni.appendChild(newdiv);
        clam.appendChild(newinput);
    }

    function removeEvent(divNum, inputName) {
        var d = document.getElementById('filelist');
        var c = document.getElementById('clamform');
        var olddiv = document.getElementById(divNum);
        var oldinput = document.getElementById(inputName);
        c.removeChild(oldinput);
        d.removeChild(olddiv);
    }

    function actions(action) {
        switch(action) {
            case 'do_delete_scan':
                document.scans.action.value="do_delete_scan";
                document.scans.submit();
                return true;
            case 'do_reschedule_scan':
                document.scans.action.value="do_reschedule_scan";
                document.scans.submit();
                return true;
        }
    }

    function checkall() {
        count = document.scans.elements.length;
            for (i=0; i < count; i++) {
                if(document.scans.elements[i].checked == 1) {
                    document.scans.elements[i].checked = 0;
                    document.scans.check_uncheck.checked = 0;
                } else {
                    document.scans.elements[i].checked = 1;
                    document.scans.check_uncheck.checked = 1;
                }
            }
    }

</script>
{/literal}
