/**
 * w-vision
 *
 * LICENSE
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that is distributed with this source code.
 *
 * @copyright  Copyright (c) 2017 Woche-Pass AG (https://www.w-vision.ch)
 */

pimcore.registerNS('pimcore.plugin.wvision');
pimcore.registerNS('wvision.version');

pimcore.plugin.wvision = Class.create(pimcore.plugin.admin, {
  getClassName: function () {
    return 'pimcore.plugin.wvision';
  },

  initialize: function () {
    pimcore.plugin.broker.registerPlugin(this);
  },

  pimcoreReady: function (params, broker) {
    var user = pimcore.globalmanager.get('user');
    var toolbar = pimcore.globalmanager.get('layout_toolbar');

    if (user.admin === true) {
      Ext.Ajax.request({
        url: '/admin/wvision/version/get-version',
        success: function (response) {
          var resp = Ext.decode(response.responseText);
          wvision.version = resp;

          this._menu = new Ext.menu.Menu({
            items: [
              {
                text: t('wvision_about'),
                iconCls: 'wvision_icon_about',
                handler: function () {
                  wvision.helpers.showAbout();
                },
              },
            ],
            shadow: false,
            cls: 'pimcore_navigation_flyout',
          });

          Ext.get('pimcore_navigation').down('ul').insertHtml('beforeEnd', '<li id="pimcore_menu_wvision" data-menu-tooltip="w-vision" class="pimcore_menu_item pimcore_menu_needs_children">' +
              '<svg id="icon-wvision" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22">' +
                '<polygon points="0,7.4 2.406,7.4 2.406,18.086 0,18.086 0,7.4 "/>' +
                '<path d="M3.116,5.863V3.756h4.118v9.809c0,1.384,1.144,2.499,2.501,2.499c1.376,0,2.549-1.039,2.549-2.509' +
                '\tV7.4h2.327l0.001,6.166c-0.011,1.403,1.13,2.499,2.533,2.499c1.35,0,2.541-1.096,2.541-2.499V7.4H22l0,6.166' +
                '\tc0,3.012-2.272,4.678-4.517,4.678c-1.71,0-3.027-0.536-3.995-2.038c-1.005,1.496-2.298,2.038-4.008,2.038' +
                '\tc-2.245,0-4.567-1.666-4.567-4.678V5.863L3.116,5.863L3.116,5.863z"/>' +
              '</svg>' +
            '</li>');
          Ext.get('pimcore_menu_wvision').on('mousedown', function (e, el) {
            toolbar.showSubMenu.call(this._menu, e, el);
          }.bind(this));
        }.bind(this),
      });
    }
  }
});

var wvisionPlugin = new pimcore.plugin.wvision();