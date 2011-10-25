{literal}
<script type='text/javascript'>
    function actions(action) {
        switch(action) {
            case 'do_maintenance_mode_on':
                document.tasks.action.value="do_maintenance_mode_on";
                document.tasks.submit();
                return true;
            case 'do_maintenance_mode_off':
                document.tasks.action.value="do_maintenance_mode_off";
                document.tasks.submit();
                return true;
        }
    }
</script>
{/literal}
