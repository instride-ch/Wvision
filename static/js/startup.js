pimcore.registerNS("pimcore.plugin.wvision");

pimcore.plugin.wvision = Class.create(pimcore.plugin.admin, {
    getClassName: function() {
        return "pimcore.plugin.wvision";
    },

    initialize: function() {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params,broker){
        var toolbar = pimcore.globalmanager.get('layout_toolbar');
        this._menu = new Ext.menu.Menu({
            items: [
                {
                    text: 'Test',
                    iconCls: 'wvision_icon_test',
                    handler: function () {
                        try {
                            pimcore.globalmanager.get('wvision_test').activate();
                        }
                        catch (e) {
                            pimcore.globalmanager.add('wvision_test', new pimcore.tool.genericiframewindow('wvision_test', '/plugin/Wvision/admin_test/test?no-cache=true', "wvision_icon_test", 'Test'));
                        }
                    }
                }
            ],
            shadow: false,
            cls: 'pimcore_navigation_flyout'
        });

        Ext.get('pimcore_navigation').down('ul').insertHtml('beforeEnd', '<li id="pimcore_menu_wvision" data-menu-tooltip="W-Vision" class="pimcore_menu_item pimcore_menu_needs_children"></li>');
        Ext.get('pimcore_menu_wvision').on('mousedown', function (e, el) {
            toolbar.showSubMenu.call(this._menu, e, el);
        }.bind(this));
    }
});

var wvisionPlugin = new pimcore.plugin.wvision();
