(function($){

    function thumbsUp(comment_id){
        thumbs(comment_id, 'up');
    }

    function thumbsDown(comment_id){
        thumbs(comment_id, 'down');
    }

    function thumbs(comment_id, upDown){
        $('#comment-' + comment_id + ' .cma-answer-rating-loading').show();
        $.post(self.location, {'cma-action': 'vote', 'cma-comment': comment_id, 'cma-value': upDown}, function(data){
            if(data.success == 1){
                $('#comment-' + comment_id + ' .cma-answer-rating-count').text(data.message);
                $().toastmessage('showSuccessToast', CMA_Variables.messages.thanks_for_voting);
            }else {
                $().toastmessage('showErrorToast', data.message);
            }
            $('#comment-' + comment_id + ' .cma-answer-rating-loading').hide();
        });
    }

    function init(){
        $('.cma-thumbs-up').on('click', function(){
            var $this, $parentTr;
            $this = $(this);
            $parentTr = $this.parents('tr.cma-comment-tr');
            if($parentTr.length)
                thumbsUp($parentTr.data('comment-id'));
        });
        $('.cma-thumbs-down').on('click', function(){
            var $this, $parentTr;
            $this = $(this);
            $parentTr = $this.parents('tr.cma-comment-tr');
            if($parentTr.length)
                thumbsDown($parentTr.data('comment-id'));
        });
    }

    $(document).ready(init);
})(jQuery);

(function(){
    var po = document.createElement('script');
    po.type = 'text/javascript';
    po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(po, s);
})();

(function(d, s, id){
    window.fbAsyncInit = function(){
        // Don't init the FB as it needs API_ID just parse the likebox
        FB.XFBML.parse();
    };

    var js, fjs = d.getElementsByTagName(s)[0];
    if(d.getElementById(id))
        return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));