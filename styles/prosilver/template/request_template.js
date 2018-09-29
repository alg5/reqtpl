(function ($) {  // Avoid conflicts with other libraries




    $(window).resize(function () {

        tplResize1();
        //alert('resolution: width = ' + $(window).width() + ', height = ' + $(window).height());
        //        var alertH = $('#reqtpl_main').height();
        //        //alert('alertH = ' + alertH);

        //        $('#reqtpl_center').css("maxHeight", alertH - 200);
        //        $('#reqtpl_center').css("overflow-y", "scroll");


    });

    $().ready(function () {
        $("#tpl_received").val(0);
        if (S_SHOW_BUTTON_FOR_NEW_TOPIC)
            ShowTemplate();
    });
    $('#reqtpl_btn').on('click', function (e) {
        e.preventDefault();
        //alert('resolution: width = ' + $(window).width() + ', height = ' + $(window).height());
        //        var screenH = $(window).height();
        //        var alertH = $('#reqtpl_main').height();
        //        if (alertH >= screenH) {
        //            //alert('alertH = ' + alertH);
        //            $('#reqtpl_center').css("maxHeight", alertH - 200);
        //            $('#reqtpl_center').css("overflow-y", "scroll");
        //        }
        ShowTemplate();
    });
    $('#reqtpl_btn_submit').on('click', function (e) {
        e.preventDefault();
        var b = validate();
        //alert(b);
        if (b) {
            append();
            $('#reqtpl_main').hide();
            $('#reqtpl_block').hide();
            $("#tpl_received").val(1);
            //alert($("#tpl_received").val);

        }


    });

    $('#reqtpl_btn_cancel').on('click', function (e) {
        e.preventDefault();
        if (S_SHOW_BUTTON_FOR_NEW_TOPIC) {
            window.location.href = U_FORUM_PATH;
            return;
        }
        $('#reqtpl_main').hide();
        $('#reqtpl_block').hide();
    });

    $(reqtpl_block).click(function (e) {
        $('#reqtpl_main').hide("slow");
        $('#reqtpl_block').hide("slow");
    });

    function validate() {
        console.log(fields);
        for (field_id in fields) {
            if (!fields.hasOwnProperty(field_id)) continue;
            var field = fields[field_id];
            var control = document.getElementById(field);
            var _val = $(control).val();
            if (important[field]) {
                if ((control.tagName == 'INPUT' && control.type == 'checkbox' && !control.checked) || (_val.length == 0)) {
                    //alert('Important field is empty: ' + this);
                    $('.error', control.parentNode).html('<br />' + lang[1]);
                    $(control).click(function () {
                        $('.error', this.parentNode).html('');
                    });
                    return false;
                }
            }
            if (typeof (regexps[field]) != 'undefined' && regexps[field].length > 0) {
                var r = new RegExp(regexps[field], 'ig');
                if (!r.test(_val)) {
                    //alert('Value is invalid: ' + this);
                    $('.error', control.parentNode).html('<br />' + lang[3]);
                    $(control).click(function () {
                        $('.error', this.parentNode).html('');
                    });
                    return false;
                }
            }
        };

        return true;

    }

    function append() {
        var _output = reqtpl_title;
        $(fields).each(function () {
            var control = document.getElementById(this);
            var _val;
            var _pattern = patterns[this];

            if (control.tagName == 'SELECT') {
                _val = $('option:selected', control).text();
            } else {
                _val = $(control).val();
            }

            if (control.tagName == 'INPUT' && control.type == 'checkbox') {
                _output += ((_output) ? "\r\n" : '') + _pattern.replace('%s', (control.checked) ? lang[4] : lang[5]);
            } else {
                if (control.id.startsWith('reqtpl_image')) {
                    $('#' + control.id + '_container .img_field').each(function () {
                        if ($('input', this).val()) {
                            _output += ((_output) ? "\r\n" : '') + _pattern.replace('%s', $('input', this).val());
                            if ($('textarea', this).val()) {
                                _output += ((_output) ? "\r\n" : '') + '[i]' + $('textarea', this).val() + '[/i]';
                            }
                        }
                    });
                } else {
                    _output += ((_output) ? "\r\n" : '') + _pattern.replace('%s', _val);
                }
            }
        });
        insert_text(_output + "\r\n");
        //$.unblockUI({ onUnblock: reqtpl.unblock });
        return true;

    }

    function tplResize() {
        var rt = $('#reqtpl_main');
        //if ($('#rt').css('display') != 'none') {
        if ($('.reqtpl_body #reqtpl_table', rt).height() > $(window).height() - 250) {
            var h = ($(window).height() - 250);
            if (h < 100) h = 100;
            $('.reqtpl_body', rt).css({ height: h + 'px' });
        } else {
            $('.reqtpl_body', rt).css({ height: '' });
        }
        // }
    }

    function tplResize1() {
        return;
        //alert('resolution: width = ' + $(window).width() + ', height = ' + $(window).height());
        //alert('div size: height = ' + $('#reqtpl_main').height());
        var winH = $(window).height();
        var tplH = $('#reqtpl_main').height();

        if (tplH >= winH) {
            //            $('#reqtpl_main').css("maxHeight", winH - 50);
            $('#reqtpl_center').css("maxHeight", tplH - 200);
            $('#reqtpl_center').css("overflow-y", "scroll");
        }
        else {
            //            $('#reqtpl_main').css("maxHeight", winH - 50);
            //            $('#reqtpl_center').css("maxHeight", tplH - 100);
            //            $('#reqtpl_center').css("overflow-y", "auto");
        }
    }

    function ShowTemplate() {
        tplResize1();
        $('#reqtpl_block').show();
        $('#reqtpl_main').show();
    }


    function changeEnable(statusEnabled) {
        // var div = $('#' + idDiv);
        if (statusEnabled) {

            $('body').children().prop('disabled', false);
            // $(div).find('dl').css('opacity', '1');
        }
        else {
            $('body').children().prop('disabled', true);
            //$(div).find('dl').css('opacity', '0.3');
        }
    }

    String.prototype.startsWith = function (str) { return (this.match("^" + str) == str) };
})(jQuery);