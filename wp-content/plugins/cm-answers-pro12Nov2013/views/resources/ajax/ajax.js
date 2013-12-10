if(!Object.keys){
    Object.keys = (function(){
        'use strict';
        var hasOwnProperty = Object.prototype.hasOwnProperty,
                hasDontEnumBug = !({toString: null}).propertyIsEnumerable('toString'),
                dontEnums = [
                    'toString',
                    'toLocaleString',
                    'valueOf',
                    'hasOwnProperty',
                    'isPrototypeOf',
                    'propertyIsEnumerable',
                    'constructor'
                ],
                dontEnumsLength = dontEnums.length;

        return function(obj){
            if(typeof obj !== 'object' && (typeof obj !== 'function' || obj === null)){
                throw new TypeError('Object.keys called on non-object');
            }

            var result = [], prop, i;

            for(prop in obj){
                if(hasOwnProperty.call(obj, prop)){
                    result.push(prop);
                }
            }

            if(hasDontEnumBug){
                for(i = 0; i < dontEnumsLength; i++){
                    if(hasOwnProperty.call(obj, dontEnums[i])){
                        result.push(dontEnums[i]);
                    }
                }
            }
            return result;
        };
    }());
}

(function($){
    "use strict";
    var CMA = {
        _url: '',
        _parent: null,
        _referer: '',
        _lastUrl: '',
        _newState: false,
        load: function(url){
            var that = this;
            $(this._parent).prepend('<div class="cma-answer-rating-loading" style="display:block"></div>');

            var dataBox = {ajax: 1};

            if(that._lastUrl){
                dataBox.referer = that._lastUrl;
            }

            $.ajax({
                url: that._url,
                data: dataBox,
                dataType: 'html',
                success: function(content){
                    $(that._parent).html(content);
                    var params = $.deparam.fragment(window.location.href);
                    if(params.page && params.page > 1)
                        $('input[name=cma-referrer]').val(window.location.pathname);
                    else
                        $('input[name=cma-referrer]').val(window.location.href);
                },
                error: function(msg){
                    console.log(msg);
                },
                complete: function(){
                    $(that._parent).find('.cma-answer-rating-loading').remove();
                    $(that._parent).find('.cma-single-login input[name=redirect_to]').val(window.location.href);
                    if(that._newState)
                        that._lastUrl = that._url;
                    that._referer = '';
                    that._url = '';
                }
            });
        }
    };

    function getElements(){
        var elements = $('.cma-container').data('pagination');
        return $.each(elements, function(key, value){
            if(typeof value === 'boolean'){
                elements[key] = Number(value);
            }
        });
    }

    $(document).ready(function($){
        $('#cma-ajax-search').live('submit', function(e){
            e.preventDefault();

            if($('#cma-ajax-search-term').val() === '')
            {
                return false;
            }
            var url = $(this).attr('action') + '?search=' + $('#cma-ajax-search-term').val();
            var params = $.deparam.querystring(url);
            var page = params.page === undefined ? 1 : params.page;
            var elements = getElements();
            elements.page = page;

            if(params.cmatag){
                elements.page = 1;
                elements.cmatag = params.cmatag;
            }

            if(params.search){
                elements.page = 1;
                elements.search = params.search;
            }
            CMA._url = $('link[rel=baseurl]').attr('href') + '/answers/?' + decodeURIComponent($.param(elements));
            CMA._parent = $(this).parents('.cma-container');
            CMA._referer = $.deparam.querystring($(this).attr('href')).referer;
            CMA._newState = true;
            $('.cma-question-form-container').show();
            if(params.search){
                $.bbq.pushState('#page=' + page + '&search=' + params.search);
            }else if(params.cmatag){
                $.bbq.pushState('#page=' + page + '&cmatag=' + params.cmatag);
            }else {
                $.bbq.pushState('#page=' + page);
            }
        });

        $('.page-numbers[href*=ajax], .cma-backlink-ajax, .ajax_tag, .cma-backlink-ajaxtag').live('click', function(e){
            e.preventDefault();
            var url = $(this).attr('href');

            var params = $.deparam.querystring(url);
            var page = params.page;
            var elements = getElements();
            ;
            elements.page = page;

            if(params.cmatag){
                elements.page = 1;
                elements.cmatag = params.cmatag;
            }

            if(params.search){
                elements.search = params.search;
            }

            if($(this).hasClass('cma-backlink-ajaxtag')){
                delete elements.cmatag;
                delete elements.search;
                elements.page = 1;
            }

            CMA._url = $('link[rel=baseurl]').attr('href') + '/answers/?' + decodeURIComponent($.param(elements));
            CMA._parent = $(this).parents('.cma-container');
            CMA._referer = $.deparam.querystring($(this).attr('href')).referer;
            CMA._newState = true;
            $('.cma-question-form-container').show();
            if(params.search){
                $.bbq.pushState('#page=' + page + '&search=' + params.search);
            }else if(params.cmatag){
                $.bbq.pushState('#page=' + page + '&cmatag=' + params.cmatag);
            }else {
                $.bbq.pushState('#page=' + page);
            }
        });

        $('.cma-answers-orderby a[href*=ajax]').live('click', function(e){
            e.preventDefault();
            var params = $.deparam.querystring($(this).attr('href'));
            var hashparams = $.deparam.fragment();
            var sort = params.sort;
            hashparams.sort = sort;
            CMA._url = $(this).attr('href');
            CMA._parent = $(this).parents('.cma-container');
            CMA._newState = false;
            $.bbq.pushState($.param.fragment(window.location.href, hashparams));
        });

        $('body').on('click', '.cma-thread-title a[href*=ajax]', function(e){
            e.preventDefault();
            var params = $(this).attr('href').split('?');
            if(params[0].indexOf('/', params[0].length - 1) !== -1)
                params[0] = params[0].substring(0, params[0].length - 1);
            var url = params[0].split('/');
            var question = url[url.length - 1];
            CMA._url = $(this).attr('href');
            CMA._referer = $.deparam.querystring($(this).attr('href')).referer;
            CMA._parent = $(this).parents('.cma-container');
            CMA._newState = false;
            $.bbq.pushState('#question=' + question);
            $('.cma-question-form-container').hide();
            return false;
        });

        $(window).bind('hashchange', function(e)
        {
            var params = $.deparam.fragment(),
                    elements = getElements();

            if(!CMA._url){
                var referer = window.location.href.split('#');
                referer = referer[0];
                if(params.page){
                    elements.page = params.page;
                    CMA._lastUrl = CMA._url = $('link[rel=baseurl]').attr('href') + '/answers/?' + decodeURIComponent($.param(elements));
                    $('.cma-question-form-container').show();
                }
                if(params.page && params.cmatag){
                    elements.page = params.page;
                    elements.cmatag = params.cmatag;
                    CMA._lastUrl = CMA._url = $('link[rel=baseurl]').attr('href') + '/answers/?' + decodeURIComponent($.param(elements));
                    $('.cma-question-form-container').show();
                }
                if(params.page && params.search){
                    elements.page = params.page;
                    elements.search = params.search;
                    CMA._lastUrl = CMA._url = $('link[rel=baseurl]').attr('href') + '/answers/?' + decodeURIComponent($.param(elements));
                    $('.cma-question-form-container').show();
                }else if(params.question){
                    CMA._lastUrl = encodeURIComponent(referer);
                    CMA._url = $('link[rel=baseurl]').attr('href') + '/answers/' + params.question;
                    if(params.sort)
                        CMA._url += '?sort=' + params.sort;
                    $('.cma-question-form-container').hide();
                }
            }
            if(!CMA._parent){
                CMA._parent = $('.cma-container');
            }

            if(!CMA._url || CMA._url.length === 0){
                if(elements.page === undefined){
                    elements.page = 1;
                }
                CMA._url = $('link[rel=baseurl]').attr('href') + '/answers/?' + decodeURIComponent($.param(elements));
            }

            CMA.load();
        });

        if(window.location.href.split('#').length > 1){
            $(window).trigger('hashchange');
        }

        $('#cma_question_type').on('change', function(){
            location.href = $(this).data('url') + 'question_type=' + $(this).val();

            return false;
        });
    });

}(jQuery));