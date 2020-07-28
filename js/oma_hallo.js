<script>
jQuery('.editable').hallo({
        plugins: {
            'halloformat': {},
            'halloblock': {},
            'hallojustify': {},
            'hallolists': {},
            //'hallolink': {},
            'halloreundo': {}
        },
        editable: true,
        placeholder: 'Hello World placeholder'
});

jQuery('.editable').bind('hallomodified', function(event, data) {
    jQuery('#modified').html("Editables modified");
});
jQuery('.editable').bind('halloselected', function(event, data) {
    jQuery('#modified').html("Selection made");
});
jQuery('.editable').bind('hallounselected', function(event, data) {
    jQuery('#modified').html("Selection removed");
});
jQuery('.editable').bind('hallorestored', function(event, data) {
    jQuery('#modified').html("restored");
});


</script>
