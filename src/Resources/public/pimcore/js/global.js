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

          Ext.get('pimcore_navigation').down('ul').insertHtml('beforeEnd', '<li id="pimcore_menu_wvision" data-menu-tooltip="w-vision" class="pimcore_menu_item pimcore_menu_needs_children"></li>');
          Ext.get('pimcore_menu_wvision').on('mousedown', function (e, el) {
            toolbar.showSubMenu.call(this._menu, e, el);
          }.bind(this));
        }.bind(this),
      });
    }
  }
});

var wvisionPlugin = new pimcore.plugin.wvision();