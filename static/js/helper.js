/**
 * w-vision
 *
 * LICENSE
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that is distributed with this source code.
 *
 * @copyright  Copyright (c) 2016 Woche-Pass AG (http://www.w-vision.ch)
 */

pimcore.registerNS('wvision.helpers.x');

wvision.helpers.showAbout = function () {

    var html = '<div class="pimcore_about_window">';
    html += '<br><img src="/plugins/Wvision/static/img/wvision.svg" style="width: 60px;"><br>';
    html += '<br><br>&copy; by w-vision, Woche-Pass AG, Sursee, Switzerland (<a href="http://www.w-vision.ch" target="_blank">www.w-vision.ch</a>)';
    html += '<a href="http://w-vision.ch/de/startseite#kennen-lernen" target="_blank">Contact</a>';
    html += '</div>';

    var win = new Ext.Window({
        title: t('wvision_about'),
        width: 500,
        height: 300,
        bodyStyle: 'padding: 10px;',
        modal: true,
        html: html
    });

    win.show();
};
