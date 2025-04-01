/*global $, jQuery, alert, console*/

    "use strict";
    var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows() );
        }
    };

    var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);


    $(document).ready(function () {

        // $(window).on('load', function() { // makes sure the whole site is loaded
        //     $('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
        //     $('body').delay(350).css({
        //         'overflow-x':'hidden',
        //         'overflow-y':'visible'
        //     });
        // });

        contactFormsConfig();
        controlTopMenu();
        copyOnClipboard();

        $('.sidenav').sidenav();
        $('.sidenav').on('close', function () {
            $('body').css({
                'overflow-x':'hidden',
                'overflow-y':'visible'
            });
        })
        $('.tooltipped').tooltip();
        $('.collapsible').collapsible();
        $('.dropdown-link_material').dropdown({
            hover: true
        });
        $('.datepicker').datepicker({
            format: 'dd.mm.yyyy 10:00',
            onClose: function(e) {
                setTimeout(function () {
                    $('body').css('overflow', 'auto')
                }, 200)

            }
        });
        $('.tabs').tabs();
        $('.lazy').Lazy();
        $('.modal').modal({
            onCloseEnd: function () {
                setTimeout(function () {
                    $('body').css('overflow-y', 'auto')
                }, 100)
            }
        });
        $('select').formSelect();

        $('.module-4--price').fixTo('body', {
            useNativeSticky: true,
            top: 0
        });

        $('.module-8__img-block').fixTo('.module-8__blocks-wrap', {
            useNativeSticky: true,
            top: 100
        });
        // var distance = $('.module-4--price').offset().top - $(window).scrollTop(),
        //     $window = $(window);
        // $window.scroll(function() {
        //     if ( $window.scrollTop() >= distance ) {
        //         $('.module-4--price').addClass('stick')
        //     } else {
        //         $('.module-4--price').removeClass('stick')
        //     }
        // });


        if (document.querySelector("#motoSoundCanvas")) {
            animationSoundMoto()
        }

        function animationSoundMoto() {
            let canvas = document.querySelector("#motoSoundCanvas");
            let ctx = canvas.getContext("2d");

            canvas.width = document.body.clientWidth;
            canvas.height = document.body.clientHeight;

            let centerX = canvas.width / 2;
            let centerY = canvas.height / 2;
            let radius = document.body.clientWidth <= 425 ? 120 : 160;
            let steps = document.body.clientWidth <= 425 ? 60 : 120;
            let interval = 360 / steps;
            let pointsUp = [];
            let pointsDown = [];
            let running = false;
            let pCircle = 2 * Math.PI * radius;
            let angleExtra = 90;

            for(let angle = 0; angle < 360; angle += interval) {
                let distUp = 1.1;
                let distDown = 0.9;

                pointsUp.push({
                    angle: angle + angleExtra,
                    x: centerX + radius * Math.cos((-angle + angleExtra) * Math.PI / 180) * distUp,
                    y: centerY + radius * Math.sin((-angle + angleExtra) * Math.PI / 180) * distUp,
                    dist: distUp
                });

                pointsDown.push({
                    angle: angle + angleExtra + 5,
                    x: centerX + radius * Math.cos((-angle + angleExtra + 5) * Math.PI / 180) * distDown,
                    y: centerY + radius * Math.sin((-angle + angleExtra + 5) * Math.PI / 180) * distDown,
                    dist: distDown
                });
            }

            const context = new AudioContext();
            const splitter = context.createChannelSplitter();

            const analyserL = context.createAnalyser();
            analyserL.fftSize = 8192;

            const analyserR = context.createAnalyser();
            analyserR.fftSize = 8192;

            splitter.connect(analyserL, 0, 0);
            splitter.connect(analyserR, 1, 0);

            const bufferLengthL = analyserL.frequencyBinCount;
            const audioDataArrayL = new Uint8Array(bufferLengthL);

            const bufferLengthR = analyserR.frequencyBinCount;
            const audioDataArrayR = new Uint8Array(bufferLengthR);

            const audio = new Audio();
            const source = context.createMediaElementSource(audio);
            source.connect(splitter);
            splitter.connect(context.destination);

            function loadAudio() {
                let soundUrl = $('#motoSound').find('source').attr('src');

                let request = new XMLHttpRequest();
                request.open("GET", soundUrl, true);
                request.responseType = "blob";
                request.onload = function() {
                    if (this.status === 200) {
                        audio.src = URL.createObjectURL(this.response);
                        audio.loop = false;
                        audio.autoplay = false;
                        audio.crossOrigin = "anonymous";
                        audio.addEventListener('canplay', function () {
                            $('.module-4__sound-wrap').show();
                        });
                        audio.addEventListener('ended', function () {
                            $('.module-4__sound-btn').removeClass('active');
                        });
                        audio.load();
                        running = true;
                    }
                }
                request.send();
            }

            $('.module-4__sound-btn').on('click', function () {
                context.resume();

                if (audio.paused) {
                    audio.play();
                } else {
                    audio.pause();
                    audio.currentTime = 0;
                }

                if (!$(this).hasClass('active')) {
                    $(this).addClass('active')
                } else {
                    $(this).removeClass('active')
                }
            })

            document.body.addEventListener('touchend', function(ev) {
                context.resume();
            });

            function drawLine(points) {
                let origin = points[0];

                ctx.beginPath();
                ctx.strokeStyle = 'rgba(255,255,255,0.5)';
                ctx.lineJoin = 'round';
                ctx.moveTo(origin.x, origin.y);

                for (let i = 0; i < points.length; i++) {
                    ctx.lineTo(points[i].x, points[i].y);
                }

                ctx.lineTo(origin.x, origin.y);
                ctx.stroke();
            }

            function connectPoints(pointsA, pointsB) {
                for (let i = 0; i < pointsA.length; i++) {
                    ctx.beginPath();
                    ctx.strokeStyle = 'rgba(255,255,255,0.5)';
                    ctx.moveTo(pointsA[i].x, pointsA[i].y);
                    ctx.lineTo(pointsB[i].x, pointsB[i].y);
                    ctx.stroke();
                }
            }

            function update(dt) {
                let audioIndex, audioValue;

                // get the current audio data
                analyserL.getByteFrequencyData(audioDataArrayL);
                analyserR.getByteFrequencyData(audioDataArrayR);

                for (let i = 0; i < pointsUp.length; i++) {
                    audioIndex = Math.ceil(pointsUp[i].angle * (bufferLengthL / (pCircle * 2))) | 0;
                    // get the audio data and make it go from 0 to 1
                    audioValue = audioDataArrayL[audioIndex] / 255;

                    pointsUp[i].dist = 1.1 + audioValue * 0.8;
                    pointsUp[i].x = centerX + radius * Math.cos(-pointsUp[i].angle * Math.PI / 180) * pointsUp[i].dist;
                    pointsUp[i].y = centerY + radius * Math.sin(-pointsUp[i].angle * Math.PI / 180) * pointsUp[i].dist;

                    audioIndex = Math.ceil(pointsDown[i].angle * (bufferLengthR / (pCircle * 2))) | 0;
                    // get the audio data and make it go from 0 to 1
                    audioValue = audioDataArrayR[audioIndex] / 255;

                    pointsDown[i].dist = 0.9 + audioValue * 0.2;
                    pointsDown[i].x = centerX + radius * Math.cos(-pointsDown[i].angle * Math.PI / 180) * pointsDown[i].dist;
                    pointsDown[i].y = centerY + radius * Math.sin(-pointsDown[i].angle * Math.PI / 180) * pointsDown[i].dist;
                }
            }

            function draw(dt) {
                requestAnimationFrame(draw);

                if (running) {
                    update(dt);
                }

                ctx.clearRect(0, 0, canvas.width, canvas.height);

                drawLine(pointsUp);
                drawLine(pointsDown);
                connectPoints(pointsUp, pointsDown);
            }

            loadAudio();
            draw();
        }

        controlTechSpecMenu()
        function controlTechSpecMenu() {

            var topMenu = $("#modalTechMenu"),
                topMenuHeight = topMenu.outerHeight()+15,
                // All list items
                menuItems = topMenu.find("a"),
                // Anchors corresponding to menu items
                scrollItems = menuItems.map(function(){
                    var item = $($(this).attr("href"));
                    if (item.length) { return item; }
                });

            $('.modal-tech-spec__text-block').scroll(function () {
                // Get container scroll position

                var fromTop = $(this).offset().top + 100;


                // Get id of current scroll item
                var cur = scrollItems.map(function(){
                  /*  console.log($(this).offset().top)*/
                    if ($(this).offset().top < fromTop) {
                        return this;
                    }
                });
                // Get the id of the current element
                cur = cur[cur.length-1];
                var id = cur && cur.length ? cur[0].id : "";
                // Set/remove active class
                menuItems
                    .parent().removeClass("active")
                    .end().filter("[href='#"+id+"']").parent().addClass("active");
            })
        }

        $('.s-map').trigger('click');

        if (window.location.hash == '#about2') {
            var curId = 'about2';

            $('.module-5').each(function () {
                $(this).removeClass('active');
                if ($(this).attr('data-id') == curId) {
                    $(this).addClass('active');

                    if ($('.module-5__bottom-slider').hasClass('slick-slider')) {
                        $('.module-5__bottom-slider').slick('unslick').slick({
                            dots: false,
                            infinite: true,
                            centerMode: true,
                            variableWidth: true,
                            arrows: true,
                            speed: 500,
                            fade: false,
                            focusOnSelect: true,
                            prevArrow: '<div class="prev-btn"><img class="image_svg" src="/img/arrow-slider-left.svg"></div>',
                            nextArrow: '<div class="next-btn"><img class="image_svg" src="/img/arrow-slider-right.svg"></div>',
                            asNavFor: '.module-5__top-slider',
                            responsive: [
                                {
                                    breakpoint: 1025,
                                    settings: {
                                        arrows: false,
                                    }
                                }
                            ]
                        });
                    }


                    if ($('.module-5__top-slider').hasClass('slick-slider')) {
                        $('.module-5__top-slider').slick('unslick').slick({
                            infinite: true,
                            asNavFor: '.module-5__bottom-slider',
                            variableWidth: false,
                            dots: false,
                            speed: 500,
                            fade: true,
                            centerMode: true,
                            focusOnSelect: true,
                            arrows: false,
                            responsive: [
                                {
                                    breakpoint: 1024,
                                    settings: {
                                        variableWidth: true,
                                        slidesToShow: 1,
                                        slidesToScroll: 1,
                                        centerMode: true,
                                        fade: false,
                                        arrows: true,
                                        prevArrow: '<div class="prev-btn"><img class="image_svg" src="/img/icon-arrow-left.svg"></div>',
                                        nextArrow: '<div class="next-btn"><img class="image_svg" src="/img/icon-arrow-right.svg"></div>'
                                    }
                                }
                            ]
                        });
                    }

                }
            })
        }


        $('.module-8__tab-link').on('click', function () {
           var curId = $(this).attr('href').slice(1);

            $('.module-5').each(function () {
                $(this).removeClass('active');
                if ($(this).attr('data-id') == curId) {
                    $(this).addClass('active');

                    if ($('.module-5__bottom-slider').hasClass('slick-slider')) {
                        $('.module-5__bottom-slider').slick('unslick').slick({
                            dots: false,
                            infinite: true,
                            centerMode: true,
                            variableWidth: true,
                            arrows: true,
                            speed: 500,
                            fade: false,
                            focusOnSelect: true,
                            prevArrow: '<div class="prev-btn"><img class="image_svg" src="/img/arrow-slider-left.svg"></div>',
                            nextArrow: '<div class="next-btn"><img class="image_svg" src="/img/arrow-slider-right.svg"></div>',
                            asNavFor: '.module-5__top-slider',
                            responsive: [
                                {
                                    breakpoint: 1025,
                                    settings: {
                                        arrows: false,
                                    }
                                }
                            ]
                        });
                    }


                    if ($('.module-5__top-slider').hasClass('slick-slider')) {
                        $('.module-5__top-slider').slick('unslick').slick({
                            infinite: true,
                            asNavFor: '.module-5__bottom-slider',
                            variableWidth: false,
                            dots: false,
                            speed: 500,
                            fade: true,
                            centerMode: true,
                            focusOnSelect: true,
                            arrows: false,
                            responsive: [
                                {
                                    breakpoint: 1024,
                                    settings: {
                                        variableWidth: true,
                                        slidesToShow: 1,
                                        slidesToScroll: 1,
                                        centerMode: true,
                                        fade: false,
                                        arrows: true,
                                        prevArrow: '<div class="prev-btn"><img class="image_svg" src="/img/icon-arrow-left.svg"></div>',
                                        nextArrow: '<div class="next-btn"><img class="image_svg" src="/img/icon-arrow-right.svg"></div>'
                                    }
                                }
                            ]
                        });
                    }

                }
            })
        });

        function copyOnClipboard() {
            $('.copy-block').on('click', function() {
                var copyText = $(this).prev('a').text();
                navigator.clipboard.writeText(copyText);
                var element = $(this);
                element.addClass('active');
                element.addClass('active-copy');
                setTimeout(function(){ element.removeClass('active'); }, 200);
                setTimeout(function(){ element.removeClass('active-copy'); }, 1000);
            })
        }

        function controlTopMenu() {
            var scrollHeight = $(window).scrollTop();

            if(scrollHeight  > 0) {
                $('.top-menu').addClass('not-top');
            } else {
                $('.top-menu').removeClass('not-top');
            }
            $(window).scroll(function() {
                scrollHeight = $(window).scrollTop();

                if(scrollHeight  > 0) {
                    $('.top-menu').addClass('not-top');
                } else {
                    $('.top-menu').removeClass('not-top');
                }
            });


        }



        $(window).resize(function() {
            addInlineSvg();
        });


        function addInlineSvg() {
            // var mySVGsToInject = document.querySelectorAll('img.image_svg');
            // SVGInjector(mySVGsToInject);
        }


        function contactFormsConfig() {
            try {
                $('input[name=url_page]').val(location.origin + location.pathname);
            }catch (e) {

            }
        }



        $('.module-2__slider-middle').slick({
            dots: false,
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            centerMode: true,
            variableWidth: true,
            arrows: true,
            speed: 1000,
            fade: false,
            prevArrow: '<div class="prev-btn"><img class="image_svg" src="/img/arrow-slider-left.svg"></div>',
            nextArrow: '<div class="next-btn"><img class="image_svg" src="/img/arrow-slider-right.svg"></div>',
            asNavFor: '.module-2__slider-top',
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        arrows: false,
                    }
                }
            ]
        });
        $('.module-2__slider-top').slick({
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            asNavFor: '.module-2__slider-middle',
            dots: false,
            speed: 1000,
            fade: true,
            centerMode: true,
            focusOnSelect: true,
            arrows: false
        });

        $('.module-2__slider-middle').on('afterChange', function(event, slick, currentSlide, nextSlide){
            $('.module-2 .counter__curent').text(currentSlide + 1)
        });


        $('.header-slider__slider').slick({
            dots: false,
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            centerMode: true,
            arrows: true,
            speed: 500,
            fade: true,
            cssEase: 'linear',
            prevArrow: '<div class="prev-btn"><img class="image_svg" src="/img/icon-arrow-left.svg"></div>',
            nextArrow: '<div class="next-btn"><img class="image_svg" src="/img/icon-arrow-right.svg"></div>',
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        arrows: false,
                    }
                }
            ]
        });

        $('.header-slider__slider').on('afterChange', function(event, slick, currentSlide, nextSlide){
            $('.header-slider .counter__curent').text(currentSlide + 1)
        });

        $('.module-5__bottom-slider').slick({
            dots: false,
            infinite: true,
            centerMode: true,
            variableWidth: true,
            arrows: true,
            speed: 500,
            fade: false,
            focusOnSelect: true,
            prevArrow: '<div class="prev-btn"><img class="image_svg" src="/img/arrow-slider-left.svg"></div>',
            nextArrow: '<div class="next-btn"><img class="image_svg" src="/img/arrow-slider-right.svg"></div>',
            asNavFor: '.module-5__top-slider',
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        arrows: false,
                    }
                }
            ]
        });
        $('.module-5__top-slider').slick({
            infinite: true,
            asNavFor: '.module-5__bottom-slider',
            variableWidth: false,
            dots: false,
            speed: 500,
            fade: true,
            centerMode: true,
            focusOnSelect: true,
            arrows: false,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        variableWidth: true,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        centerMode: true,
                        fade: false,
                        arrows: true,
                        prevArrow: '<div class="prev-btn"><img class="image_svg" src="/img/icon-arrow-left.svg"></div>',
                        nextArrow: '<div class="next-btn"><img class="image_svg" src="/img/icon-arrow-right.svg"></div>'
                    }
                }
            ]
        });

        $('.module-5__top-slider').on('afterChange', function(event, slick, currentSlide, nextSlide){
            $('.module-5 .counter__curent').text(currentSlide + 1)
        });

        $('.module-6__slider-block').slick({
            dots: false,
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            centerMode: true,
            arrows: true,
            speed: 500,
            fade: true,
            cssEase: 'linear',
            asNavFor: '.module-6__text-slider',
            prevArrow: $('.module-6__arrow.prev-btn'),
            nextArrow: $('.module-6__arrow.next-btn'),
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        arrows: false,
                        fade: false,
                        variableWidth: true,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        centerMode: true
                    }
                }
            ]
        });
        $('.module-6__text-slider').slick({
            infinite: true,
            asNavFor: '.module-6__slider-block',
            variableWidth: false,
            dots: false,
            speed: 500,
            fade: true,
            centerMode: true,
            focusOnSelect: true,
            arrows: false
        });

        $('.module-6__slider-block').on('afterChange', function(event, slick, currentSlide, nextSlide){
            $('.module-6 .counter__curent').text(currentSlide + 1)
        });

        $('.module-7__slider-block').slick({
            dots: false,
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            centerMode: true,
            arrows: true,
            speed: 500,
            fade: true,
            cssEase: 'linear',
            prevArrow: $('.module-7__arrow.prev-btn'),
            nextArrow: $('.module-7__arrow.next-btn'),
            asNavFor: '.module-7__text-slider',
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        arrows: false,
                        fade: false,
                        variableWidth: true,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        centerMode: true
                    }
                }
            ]
        });
        $('.module-7__text-slider').slick({
            infinite: true,
            asNavFor: '.module-7__slider-block',
            variableWidth: false,
            dots: false,
            speed: 500,
            fade: true,
            centerMode: true,
            focusOnSelect: true,
            arrows: false
        });

        $('.module-7__slider-block').on('afterChange', function(event, slick, currentSlide, nextSlide){
            $('.module-7 .counter__curent').text(currentSlide + 1)
        });


        $('.s-timeline__bottom-slider').slick({
            dots: false,
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            centerMode: true,
            variableWidth: true,
            arrows: true,
            speed: 500,
            fade: false,
            focusOnSelect: true,
            prevArrow: '<div class="prev-btn"><img class="image_svg" src="/img/arrow-slider-left.svg"></div>',
            nextArrow: '<div class="next-btn"><img class="image_svg" src="/img/arrow-slider-right.svg"></div>',
            asNavFor: '.s-timeline__top-slider',
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        arrows: false,
                    }
                }
            ]
        });
        $('.s-timeline__top-slider').slick({
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            asNavFor: '.s-timeline__bottom-slider',
            dots: false,
            speed: 500,
            fade: true,
            centerMode: true,
            focusOnSelect: true,
            arrows: true,
            prevArrow: $('.s-timeline__top-arrow.prev-btn'),
            nextArrow: $('.s-timeline__top-arrow.next-btn')
        });

        $('.s-timeline__top-slider').on('afterChange', function(event, slick, currentSlide, nextSlide){
            $('.s-timeline__top-counter .counter__curent').text(currentSlide + 1)
        });


        // $(".rotation").brazzersCarousel();


        controlAnimatedScroll();
        function controlAnimatedScroll() {
            $('.animated-scroll').on('click', function (e) {
                e.preventDefault();
                var linkHref = $(this).attr('href');
                $('html, body').animate({
                    scrollTop: $(linkHref).offset().top - 60
                }, 500);
            });

            $('.back-top__link').on('click', function (e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, 500);
            });
        }

        controlStock();
        function controlStock() {
            $('#select_stock').on('change', function () {
                var curId = $(this).val();
                if (curId == 'all') {
                    $('.s-stock__block').each(function () {
                        $(this).removeClass('active');
                        $(this).addClass('active');
                    });

                    return;
                }
                $('.s-stock__block').each(function () {
                    $(this).removeClass('active')
                    if ($(this).attr('data-id') == curId) {
                        $(this).addClass('active');
                    }
                })
            })
        }


        modelsMenuControl();
        function modelsMenuControl() {
            function hideMoto() {
                $('.models-block').each(function () {
                    $(this).removeClass('active')
                })
                $('.models-second-link').each(function () {
                    $(this).removeClass('active')
                });
                $('.models-blocks-wrap').removeClass('active');
                $('.models-dropdown').removeClass('show-bike');
            }

            $('.top-menu__link').on('mouseenter', function () {
                if (!$(this).hasClass('top-menu__link--models')) {
                    $('.models-dropdown').removeClass('active');
                    $('.top-menu__link--models').removeClass('active')
                    hideMoto();
                } else {
                    $('.models-dropdown').addClass('active')
                    $('.top-menu__link--models').addClass('active')
                }
            });
            $('.top-menu__link-phone').on('mouseenter', function () {
                $('.models-dropdown').removeClass('active');
                $('.top-menu__link--models').removeClass('active')
                hideMoto();
            });
            $('.anqors-wrap').on('mouseenter', function () {
                $('.models-dropdown').removeClass('active');
                $('.top-menu__link--models').removeClass('active')
                hideMoto();
            });

            $('header').on('mouseenter', function () {
                $('.models-dropdown').removeClass('active');
                $('.top-menu__link--models').removeClass('active')
                hideMoto();
            });
            $('section').on('mouseenter', function () {
                $('.models-dropdown').removeClass('active');
                $('.top-menu__link--models').removeClass('active')
                hideMoto();
            });
            $('footer').on('mouseenter', function () {
                $('.models-dropdown').removeClass('active');
                $('.top-menu__link--models').removeClass('active')
                hideMoto();
            });

            $('.models-main-link').on('click', function (e) {
                e.preventDefault();
            });
            $('.models-main-link').on('mouseenter', function () {
                $('.models-second-links').each(function () {
                    $(this).removeClass('active')
                });

                $('.models-main-link').each(function () {
                    $(this).removeClass('active')
                });
                $(this).addClass('active');

                $(this).closest('.models-main-item').find('.models-second-links').addClass('active');
                hideMoto();
            });

            $('.models-second-link').on('mouseenter', function () {
                if ($(this).closest('.models-second-links').hasClass('active')) {
                    $('.models-blocks-wrap').addClass('active');

                    $('.models-second-link').each(function () {
                        $(this).removeClass('active')
                    });


                    hideMoto();
                    $(this).addClass('active');
                    $('.models-dropdown').addClass('show-bike');
                    var activeMoto = $(this).attr('data-href')
                    $('.models-block').each(function () {
                        if ($(this).attr('data-id') == activeMoto) {
                            $(this).addClass('active');
                        }
                    })
                }
            });
        }

        checkMotoRide();
        function checkMotoRide() {
            $('.check-ride').on('click', function (e) {
                e.preventDefault();
                var curId = $(this).closest('.module-9__block').attr('id')
                if ($(this).hasClass('active')) {
                    $(this).removeClass('active').closest('.module-9__block').removeClass('checked');
                } else {
                    $('.check-ride').each(function () {
                        $(this).removeClass('active').closest('.module-9__block').removeClass('checked');
                    });
                    $(this).addClass('active').closest('.module-9__block').addClass('checked');
                    $('html, body').animate({scrollTop: $('#rideFormSection').offset().top + 100}, 500);
                }

                $('#model_r').val(curId).formSelect();
            })
        }

        $('.top-menu__link--models').on('click', function (e) {
            e.preventDefault();
        });

        detectScrollSide();
        function detectScrollSide() {
            var lastScrollTop = 0;
            $(window).scroll(function(event){
                var st = $(this).scrollTop();
                if ((st > lastScrollTop) && ($(this).scrollTop() > 400)){
                    $('.top-menu').css('transform', 'translateY(-100%)')
                } else {
                    $('.top-menu').css('transform', 'translateY(0)')
                }
                lastScrollTop = st;
            });
        }

        controlColor();
        function controlColor() {
            $('.module-4__color-line').on('click', function(e) {
                e.preventDefault();

                if ($(this).attr('color-price')) {
                    var colorPrice = $(this).find('.color-price').text();
                    $('.module-4__block .price').text(colorPrice);
                }

                var activeImage = $('.rotation.active').find('.single-image.active').attr('adc-value');
                var activeColor = $(this).attr('data-color');

                $('.module-4__color-line').each(function () {
                    $(this).removeClass('active');
                });
                $(this).addClass('active');

                $('.module-4__rotation-slider').each(function () {
                    $(this).removeClass('active');
                    if ($(this).attr('data-color') == activeColor) {
                        $(this).addClass('active');
                    }
                });

                // $('.module-4__rotation-slider.active').find('.single-image').each(function () {
                //     $(this).removeClass('active');
                //     if ($(this).attr('adc-value') == activeImage) {
                //         $(this).addClass('active')
                //     }
                // })
            });
        }


        productManager();
        function productManager() {
            if($('.product').length) {
                var dragOn360 = false;


                $('.module-360 .model-container .version-container').each(function() {
                    $(this).find('.single-image:eq(3)').addClass('active');
                });

                $('.module-360 .model-container .version-container .single-image').on('dragstart', function(event) { event.preventDefault(); });



                $('.module-360 .model-container .version-container').each(function() {
                    var current360container = $(this);
                    var imagesCounter = 0;
                    var currentImage = 3;
                    var nextImage;
                    var totalImages = current360container.find('.single-image').length;
                    var startingX;

                    current360container.find('.single-image').each(function() {
                        $(this).attr('adc-value', imagesCounter);
                        imagesCounter++;
                    });

                    current360container.bind('mousedown touchstart', function(event) {
                        if(event.pageX) {
                            startingX = event.pageX;
                        } else {
                            startingX = event.originalEvent.touches[0].pageX;
                        }

                        $(window).bind('mousemove touchmove', function(e) {
                            dragOn360 = true;

                            if(e.pageX) {
                                var x = e.pageX;
                            } else {
                                var x = e.originalEvent.touches[0].pageX;
                            }

                            move360model(current360container,x);
                        });
                        return false;
                    });

                    function move360model(container,x) {
                        if (dragOn360) {
                            if(x < (startingX - 10)) {
                                if(currentImage == (totalImages - 1)) {
                                    nextImage = 0;
                                } else {
                                    nextImage = currentImage + 1;
                                }

                                container.find('.single-image:eq(' + currentImage + ')').removeClass('active');
                                container.find('.single-image:eq(' + nextImage + ')').addClass('active');

                                currentImage = nextImage;

                                startingX = x;
                            } else if(x > (startingX + 10))  {
                                if(currentImage == 0) {
                                    nextImage = (totalImages - 1);
                                } else {
                                    nextImage = currentImage - 1;
                                }

                                container.find('.single-image:eq(' + currentImage + ')').removeClass('active');
                                container.find('.single-image:eq(' + nextImage + ')').addClass('active');

                                currentImage = nextImage;

                                startingX = x;
                            }
                        }
                    }

                    current360container.bind('mouseup touchend', function() {
                        dragOn360 = false;
                        $(window).unbind("mousemove touchmove");
                    });
                });
            }
        }

        $('.module-5-popup-link').magnificPopup({
            type: 'image'
        });

        addInlineSvg();

    });

