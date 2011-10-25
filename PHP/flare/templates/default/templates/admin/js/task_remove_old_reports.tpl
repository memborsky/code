{literal}

<script type='text/javascript'>
    function change_report_img(graph) {
        document.getElementById("report_graph_viewer").src = graph;
    }
    function checkall() {
        count = document.maintenance.elements.length;
            for (i=0; i < count; i++) {
                if(document.maintenance.elements[i].checked == 1) {
                    document.maintenance.elements[i].checked = 0;
                    document.maintenance.check_uncheck.checked = 0;
                } else {
                    document.maintenance.elements[i].checked = 1;
                    document.maintenance.check_uncheck.checked = 1;
                }
            }
    }
</script>

{/literal}
