{literal}

<script type='text/javascript'>
    function actions(action) {
        switch(action) {
            case 'dead':
                document.task.type.value="dead";
                document.task.submit();
                return true;
            case 'missing_file':
                document.task.type.value="missing_file";
                document.task.submit();
                return true;
            case 'missing_dir':
                document.task.type.value="missing_dir";
                document.task.submit();
                return true;
        }
    }
</script>

{/literal}
