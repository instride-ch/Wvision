pimcore.registerNS("pimcore.plugin.wvision");

pimcore.plugin.wvision = Class.create(pimcore.plugin.admin, {
    getClassName: function() {
        return "pimcore.plugin.wvision";
    },

    initialize: function() {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params,broker){
        var user = pimcore.globalmanager.get('user');
        var toolbar = pimcore.globalmanager.get('layout_toolbar');

        if (user.admin === true) {
            this._menu = new Ext.menu.Menu({
                items: [
                    {
                        text: t('wvision_settings'),
                        iconCls: 'wvision_icon_settings',
                        handler: function () {
                            try {
                                pimcore.globalmanager.get('wvision_settings').activate();
                            }
                            catch (e) {
                                pimcore.globalmanager.add('wvision_settings', new pimcore.plugin.wvision.settings());
                            }
                        }
                    },
                    // {
                    //     text: t('wvision_console'),
                    //     iconCls: 'wvision_icon_console',
                    //     handler: function () {
                    //         try {
                    //             pimcore.globalmanager.get('wvision_console').activate();
                    //         }
                    //         catch (e) {
                    //             pimcore.globalmanager.add('wvision_console', new pimcore.tool.genericiframewindow('wvision_console', '/plugin/Wvision/admin_console/console?no-cache=true', "wvision_icon_console", 'Console'));
                    //         }
                    //     }
                    // },
                    // {
                    //     text: t('wvision_update'),
                    //     iconCls: 'wvision_icon_force_update',
                    //     handler: function () {
                    //         try {
                    //             pimcore.globalmanager.get('wvision_update').activate();
                    //         }
                    //         catch (e) {
                    //             pimcore.globalmanager.add('wvision_update', new pimcore.tool.genericiframewindow('wvision_update', '/plugin/Wvision/admin_console/force-update?no-cache=true', "wvision_icon_update", 'Update'));
                    //         }
                    //     }
                    // },
                    {
                        text: t('wvision_about'),
                        iconCls: 'wvision_icon_about',
                        handler: function () {
                            wvision.helpers.showAbout();
                        }
                    }
                ],
                shadow: false,
                cls: 'pimcore_navigation_flyout'
            });

            Ext.get('pimcore_navigation').down('ul').insertHtml('beforeEnd', '<li id="pimcore_menu_wvision" data-menu-tooltip="w-vision" class="pimcore_menu_item pimcore_menu_needs_children"></li>');
            Ext.get('pimcore_menu_wvision').on('mousedown', function (e, el) {
                toolbar.showSubMenu.call(this._menu, e, el);
            }.bind(this));
        }
    }
});

var wvisionPlugin = new pimcore.plugin.wvision();
