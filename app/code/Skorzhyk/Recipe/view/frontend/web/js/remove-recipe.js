define(['jquery', 'mage/translate'], function ($, $t) {
    'use strict';

    return function (config, element) {
        let link = $(element);
        link.click(function () {
            let message = $t('Do you want to remove this Recipe?');
            return !!confirm(message);
        });
    };
});