/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

let page = require('page');
let tocbot = require('tocbot');
let avatar = $('#avatar>img');
toc();
search();
linkTarget();
highlight();
mobileHeader();
$(window).on('scroll', function () {
    let toc = $('.toc');
    if (toc.length>0) {
        if ($(window).scrollTop() > 268) {
            toc.addClass('is-position-fixed');
            toc.css('left', $('article').offset().left - 300);
        } else {
            toc.removeClass('is-position-fixed');
            toc.css('left', null);
        }
    }
    if ($(window).scrollTop() > 0) {
        if (!$('.toTop').hasClass('toTop-show')) {
            $('.toTop').addClass('toTop-show');
        }
    } else {
        if ($('.toTop').hasClass('toTop-show')) {
            $('.toTop').removeClass('toTop-show');
        }
    }
});
$(window).on('resize', function () {
    let toc = $('.toc');
    if (toc.length>0) {
        if ($(window).scrollTop() > 268) {
            toc.css('left', $('article').offset().left - 300);
        }
    }
    mobileHeader();
});
$('.toTop').on('click', function () {
    scrollToTop($(window).scrollTop());
});
page.base('/');
page('categories/:slug', send);
page('posts/:slug', send);
page('tags/:slug', send);
page('search/:keyword', send);
page('/', send);
page({
    dispatch: false
});
function send (ctx) {
    avatar.addClass('avatar-rotate');
    scrollToTop($(window).scrollTop());
    let path = (ctx.path === '/') ? '/' : '/' + ctx.path;
    $.ajax({
        type: 'POST',
        url: path,
        success: changeView,
        error: function () {
            window.location.reload();
        }
    });
}
function changeView ($response) {
    let data = JSON.parse($response);
    for (let id in data) {
        if (id === 'title') {
            $('title').html(data[id]);
            continue;
        }
        if (id === 'main') {
            $('#main').fadeOut('0.5', function () {
                $('#main').html(data[id]);
                $('#main').fadeIn('0.5');
                toc();
                linkTarget();
                highlight();
            });
            continue;
        }
        $('#'+id).html(data[id]);
    }
    avatar.one('animationiteration', function () {
        avatar.removeClass('avatar-rotate');
    });
    search();
    mobileHeader();
    if ($('.nav-show').length > 0) {
        $('.mobile-header').click()
    }
}
function search() {
    $('.search>span').on('click', function () {
        let input = $('.search>input');
        let keyword = input.val();
        if (keyword.length >= 3 && keyword.length <= 12) {
            page.redirect('/search/' + keyword);
        } else {
            input.val('');
            let tip = keyword.length < 3 ? '搜索关键字太短!' : '搜索关键字太长!';
            input.attr('placeholder', tip);
        }
    });
    $(".search>input").on('keydown', function (e) {
        let curKey = e.which;
        if (curKey === 13) {
            $(".search>span").click();
            return false;
        }
    });
}
function highlight() {
    $('pre code').each(function(i, block) {
        hljs.highlightBlock(block);
    });
}
function toc() {
    let option = {
        tocSelector: '.toc',
        contentSelector: '#post',
        headingSelector: 'h1, h2, h3',
    };
    if ($('.toc').length>0 && $('#post').length>0) {
        if (window.tocbot.option) {
            tocbot.refresh(option);
        } else {
            tocbot.init(option);
        }
        $('.toc a').each(function () {
            let a = $(this);
            a.attr('title', a.text());
        })
    }
}
function mobileHeader() {
    if ($(window).width() < 800) {
        if ($('.mobile-header').length === 0) {
            $('#navbar').prepend('<div class="mobile-header icon-navicon"><h1>'+ $('#name>h1').text() +'</h1></div>');
            $('.mobile-header').on('click', function () {
                $('#navbar').toggleClass('nav-show');
            })
        }
    } else {
        $('.mobile-header').remove();
    }
}
function scrollToTop(y) {
    let x = y/6 + 8;
    y = (y-x>0) ? y-x : 0;
    $(window).scrollTop(y);
    if (y !== 0) {
        setTimeout(function () {
            scrollToTop(y);
        }, 20);
    }
}
function linkTarget() {
    let home = $('#name>h1>a').attr('href');
    $('a').each(function () {
        let a = $(this);
        let href = a.attr('href');
        if (href.indexOf(home) !== 0) {
            a.attr('target', '_blank');
        }
    })
}