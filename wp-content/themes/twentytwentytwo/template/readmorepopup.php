<?php
/*
Template Name: Readmorepopup
*/
get_header();
?>
<style type="text/css">
	.hidden { display: none;}
.readmore { margin: 0 5px;}
</style>

<script type="text/javascript">
	jQuery(function () {
    
    var maxL = 200;
    
    jQuery('.content').each(function () {
        
        var text = $(this).text();
        if(text.length > maxL) {
            
            var begin = text.substr(0, maxL),
                end = text.substr(maxL);

            jQuery(this).html(begin)
                .append($('<a class="readmore"/>').attr('href', '#').html('read more...'))
                .append($('<div class="hidden" />').html(end));
                
            
        }
        
        
    });
            
    jQuery(document).on('click', '.readmore', function () {
				// $(this).next('.readmore').fadeOut("400");
        jQuery(this).next('.hidden').slideToggle(400);
    })        
    
    
})
</script>
<div class="content">
Hi. Can anyone tell me how its possible to limit the amount of text characters in a div. I want to be able to limit to say 200 characters, then display a 'read more' link, when clicked it slides down the rest of the text. Hi. Can anyone tell me how its possible to limit the amount of text characters in a div. I want to be able to limit to say 200 characters, then display a 'read more' link, when clicked it slides down thides down the rest of the text.
</div>





<?php
get_footer();
?>