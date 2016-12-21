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
    html += '<br /><img src="/plugins/Wvision/static/img/logo.svg" style="width: 300px;"><br />';
    //html += '<br /><strong>Version: ' + wvision.settings.version + '</strong>';
    //html += '<br /><strong>Build: ' + wvision.settings.build + '</strong>';
    html += '<br /><br />&copy; by w-vision, Sursee, Switzerland (<a href="http://www.w-vision.ch/" target="_blank">w-vision.ch</a>)';
    html += '<br />a proud member of the <a href="http://woche-pass.ch/" target="_blank">Woche-Pass AG</a>';
    html += '<br><br><a href="http://w-vision.ch/de/startseite#kennen-lernen" target="_blank">Contact</a> | ';
    html += '<a href="http://w-vision.ch/de/startseite#das-unternehmen" target="_blank">Team</a>';
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
