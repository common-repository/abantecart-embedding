jQuery(document)
    .on('click', '#abc_add_url', function () {
            $ = jQuery;
            var inp = jQuery('form.abc-settings').find('input[name^=Abantecart_settings]').last();
            var clone = inp.clone();
            clone.attr('name', 'Abantecart_settings[abantecart_store_url][]')
                .val('');
            var d = $('<div class="padding-2 margin-top-4 alignleft abc_url_div"></div>');
            d.append(clone);
            d.append($('<a href="Javascript:void(0);" class="btn btn-block abc_remove_url">[-]</a>'));
            inp.parent().parent().append(d);
        }
    );

jQuery(document)
    .on('click', '.abc_remove_url', function (e) {
            jQuery(this).parent().remove();
            return false;
        }
    );
