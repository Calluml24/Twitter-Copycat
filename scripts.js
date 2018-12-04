//TAKEN FROM - https://www.codexworld.com/load-data-on-page-scroll-jquery-ajax-php-mysql/
//THIS IS A PAGE SCROLLING FUNCTION
/*$(document).ready(function(){
    $(window).scroll(function(){
        var lastID = $('.load-more').attr('lastID');
        if(($(window).scrollTop() == $(document).height() - $(window).height()) && (lastID != 0)){
            $.ajax({
                type:'POST',
                url:'getData.php',
                data:'postid='+lastID,
                beforeSend:function(){
                    $('.load-more').show();
                },
                success:function(html){
                    $('.load-more').remove();
                    $('#postList').append(html);
                }
            });
        }
    });
});
*/

function usernameTaken() {
	alert("Sorry, This username is taken! Please try another");
}
