var active_menu = '';
var cookiebot_content = '';

jQuery(function ($) {
    $(document).ready(function () {
        /**
         * Create colorbox modal
         */
        $('#cookiebot-media').on('click', function () {
            $(this).colorbox({
                inline: true,
                href: "#cookiebot_media_content",
                width: "80%",
                height: "80%",
                transition: "fade",
                scrolling: false,
                opacity: 0.2
            });
        });

        /**
         * Close the colorbox
         */
        $('#cookiebot_media_content .media-modal-close').on('click', function () {
            $.colorbox.close();
        });

        /**
         * Change active menu item
         */
        $('#cookiebot_media_content .media-menu-item').on('click', function () {
            $('#cookiebot_media_content .media-menu-item').each(function () {
                $(this).removeClass('active');
            });

            $(this).addClass('active');

            $('.widget_content').hide();

            active_menu = $(this).data('name');

            $('.media-frame-title h1').html(ucfirst(active_menu));

            $('.' + active_menu + '_content').show();
        });

        /**
         * Add iframe, placeholder or img to content
         */
        $('#cookiebot_media_content .media-button-insert').on('click', function () {
            /**
             * get current active menu item
             */
            get_active_menu();

            var cookie_consent = [];

            $('.cookieconsent:checked').each(function () {
                cookie_consent.push($(this).val());
            });

            switch (active_menu) {
                case 'iframe':
                    cookiebot_content = $('#iframe_content').val();

                    cookiebot_content = cookiebot_content.replace('src=', 'data-src=');
                    cookiebot_content = cookiebot_content.replace('<iframe ', '<iframe data-cookieconsent="' + cookie_consent.join(',') + '" ');
                    break;
                case 'placeholder':
                    var cookie_class = '';

                    cookie_consent.forEach(function (type) {
                        cookie_class += 'cookieconsent-optout-' + type + ' ';
                    });

                    if (cookie_class !== '') {
                        cookie_class = cookie_class.slice(0, -1);
                    }

                    cookiebot_content = $('#placeholder_content').val();

                    cookiebot_content = cookiebot_content.replace('[renew_consent]', '<a href="javascript:Cookiebot.renew()">');

                    cookiebot_content = cookiebot_content.replace('[/renew_consent]', '</a>');

                    cookiebot_content = cookiebot_content.replace('%cookie_types', cookie_consent.join(','));

                    cookiebot_content = '<div class="' + cookie_class + '">' + cookiebot_content + '</div>';
                    break;
                case 'image':
                    cookiebot_content = $('#image_content').val();

                    cookiebot_content = '<img data-src="' + cookiebot_content + '" data-cookieconsent="' + cookie_consent.join(',') + '">';
                    break;
            }

            wp.media.editor.insert(cookiebot_content);
            $.colorbox.close();
        });

        /**
         * Add image editor
         */
        $('.image_link').on('click', function () {
            if (this.window === undefined) {
                this.window = wp.media({
                    title: 'Insert a media',
                    library: {type: 'image'},
                    multiple: false,
                    button: {text: 'Insert'}
                });

                var self = this; // Needed to retrieve our variable in the anonymous function below
                this.window.on('select', function () {
                    var first = self.window.state().get('selection').first().toJSON();
                    $('#image_content').val(first.url);
                });
            }

            this.window.open();
            return false;
        });

        /**
         * Helptip for placeholder
         */
        $('.help-tip').tipTip({
            'maxWidth': 300,
            'fadeIn': 50,
            'fadeOut': 50,
            'delay': 200
        });


    });

    /**
     * Get active cookiebot modal menu
     *
     * @return string   iframe, placeholder or image
     */
    function get_active_menu() {
        active_menu = $('#cookiebot_media_content .media-menu-item.active').data('name');
    }

    /**
     * Make the first character uppercase
     *
     * @param str
     * @return {string}
     */
    function ucfirst(str) {
        var firstLetter = str.substr(0, 1);
        return firstLetter.toUpperCase() + str.substr(1);
    }
});