require(['jQuery'], function($) {
    $(document).ready(function() {
        function dropdown_click(){
            $(this).css('background-color','green');
            var id = $(this).attr('id');
            id.replace('groupformation_tracker_instance', '');
            var contents = $('#groupformation_tracker_dropdown_content').children();
            for (var i = 0; i < contents.length; i++){
                var tmpID = contents[i].attr('id');
                tmpID.replace('groupformation_tracker_dropdown_content', '');
                if (tmpID == id){
                    contents[i].style.display = 'block';
                } else {
                    contents[i].style.display = 'none';
                }
            }
        };
    });
});