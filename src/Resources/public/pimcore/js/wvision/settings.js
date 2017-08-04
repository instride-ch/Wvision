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

pimcore.registerNS('pimcore.plugin.wvision.settings');
pimcore.plugin.wvision.settings = Class.create({

  shopPanels: {},

  initialize: function () {
    this.getData();
  },

  getData: function () {
    Ext.Ajax.request({
      url: '/admin/wvision/settings/get',
      success: function (response) {

        this.data = Ext.decode(response.responseText);

        this.getTabPanel();

      }.bind(this),
    });
  },

  getValue: function (key) {
    var current = null;

    if (this.data.settings.hasOwnProperty(key)) {
      current = this.data.settings[key];
    }

    if (typeof current != 'object' && typeof current != 'array' && typeof current != 'function') {
      return current;
    }

    return '';
  },

  getTabPanel: function () {
    if (!this.panel) {
      var me = this;

      this.panel = Ext.create('Ext.panel.Panel', {
        id: 'wvision_settings',
        title: t('wvision_settings'),
        iconCls: 'pimcore_icon_system',
        border: false,
        layout: 'fit',
        closable: true,
      });

      var tabPanel = Ext.getCmp('pimcore_panel_tabs');
      tabPanel.add(this.panel);
      tabPanel.setActiveItem('wvision_settings');

      this.panel.on('destroy', function () {
        pimcore.globalmanager.remove('wvision_settings');
      }.bind(this));

      this.layout = Ext.create('Ext.panel.Panel', {
        bodyStyle: 'padding:20px 5px 20px 5px;',
        border: false,
        autoScroll: true,
        forceLayout: true,
        defaults: {
          forceLayout: true,
        },
        buttons: [
          {
            text: t('save'),
            handler: this.save.bind(this),
            iconCls: 'pimcore_icon_apply',
          },
        ],
      });

      this.settingsPanel = Ext.create('Ext.form.Panel', {
        border: false,
        autoScroll: true,
        forceLayout: true,
        defaults: {
          forceLayout: true,
        },
        fieldDefaults: {
          labelWidth: 250,
        },
        items: [
          {
            xtype: 'fieldset',
            title: t('wvision_settings_authentication'),
            collapsible: true,
            collapsed: true,
            autoHeight: true,
            defaultType: 'textfield',
            defaults: {width: 600},
            items: [
              {
                fieldLabel: t('wvision_settings_authentication_user_folder'),
                name: 'APPLICATION.AUTH.USER_FOLDER',
                cls: 'input_drop_target',
                value: this.getValue('APPLICATION.AUTH.USER_FOLDER'),
                xtype: 'textfield',
                listeners: {
                  render: function (el) {
                    new Ext.dd.DropZone(el.getEl(), {
                      reference: this,
                      ddGroup: 'element',
                      getTargetFromEvent: function (e) {
                        return this.getEl();
                      }.bind(el),

                      onNodeOver: function (target, dd, e, data) {
                        return Ext.dd.DropZone.prototype.dropAllowed;
                      },

                      onNodeDrop: function (target, dd, e, data) {
                        var record = data.records[0];
                        var data = record.data;

                        if (data.elementType == 'object') {
                          this.setValue(data.path);
                          return true;
                        }

                        return false;
                      }.bind(el),
                    });
                  },
                },
              },
              {
                xtype: 'checkbox',
                fieldLabel: t('wvision_settings_authentication_publish_directly'),
                name: 'APPLICATION.AUTH.PUBLISH_DIRECTLY',
                checked: this.getValue('APPLICATION.AUTH.PUBLISH_DIRECTLY'),
              },
              {
                xtype: 'checkbox',
                fieldLabel: t('wvision_settings_authentication_delete_after_unregister'),
                name: 'APPLICATION.AUTH.DELETE_AFTER_UNREGISTER',
                checked: this.getValue('APPLICATION.AUTH.DELETE_AFTER_UNREGISTER'),
              },
            ],
          },
          {
            xtype: 'fieldset',
            title: t('wvision_settings_newsletter'),
            collapsible: true,
            collapsed: true,
            autoHeight: true,
            defaultType: 'textfield',
            defaults: {width: 600},
            items: [
              {
                fieldLabel: t('wvision_settings_newsletter_user_folder'),
                name: 'APPLICATION.NEWSLETTER.USER_FOLDER',
                cls: 'input_drop_target',
                value: this.getValue('APPLICATION.NEWSLETTER.USER_FOLDER'),
                xtype: 'textfield',
                listeners: {
                  render: function (el) {
                    new Ext.dd.DropZone(el.getEl(), {
                      reference: this,
                      ddGroup: 'element',
                      getTargetFromEvent: function (e) {
                        return this.getEl();
                      }.bind(el),

                      onNodeOver: function (target, dd, e, data) {
                        return Ext.dd.DropZone.prototype.dropAllowed;
                      },

                      onNodeDrop: function (target, dd, e, data) {
                        var record = data.records[0];
                        var data = record.data;

                        if (data.elementType == 'object') {
                          this.setValue(data.path);
                          return true;
                        }

                        return false;
                      }.bind(el),
                    });
                  },
                },
              },
            ],
          },
          {
            xtype: 'fieldset',
            title: t('wvision_settings_notification'),
            collapsible: true,
            collapsed: true,
            autoHeight: true,
            defaultType: 'textfield',
            defaults: {width: 600},
            items: [
              {
                xtype: 'checkbox',
                fieldLabel: t('wvision_settings_notification_close_button'),
                name: 'APPLICATION.NOTIFICATION.CLOSE_BUTTON',
                checked: this.getValue('APPLICATION.NOTIFICATION.CLOSE_BUTTON'),
              },
              {
                xtype: 'checkbox',
                fieldLabel: t('wvision_settings_notification_add_behavior_on_click'),
                name: 'APPLICATION.NOTIFICATION.ADD_BEHAVIOR_ON_CLICK',
                checked: this.getValue('APPLICATION.NOTIFICATION.ADD_BEHAVIOR_ON_CLICK'),
              },
              {
                xtype: 'checkbox',
                fieldLabel: t('wvision_settings_notification_debug'),
                name: 'APPLICATION.NOTIFICATION.DEBUG',
                checked: this.getValue('APPLICATION.NOTIFICATION.DEBUG'),
              },
              {
                xtype: 'checkbox',
                fieldLabel: t('wvision_settings_notification_progressbar'),
                name: 'APPLICATION.NOTIFICATION.PROGRESSBAR',
                checked: this.getValue('APPLICATION.NOTIFICATION.PROGRESSBAR'),
              },
              {
                xtype: 'checkbox',
                fieldLabel: t('wvision_settings_notification_prevent_duplicates'),
                name: 'APPLICATION.NOTIFICATION.PREVENT_DUPLICATES',
                checked: this.getValue('APPLICATION.NOTIFICATION.PREVENT_DUPLICATES'),
              },
              {
                xtype: 'checkbox',
                fieldLabel: t('wvision_settings_notification_button_force_closing'),
                name: 'APPLICATION.NOTIFICATION.BUTTON_FORCE_CLOSING',
                checked: this.getValue('APPLICATION.NOTIFICATION.BUTTON_FORCE_CLOSING'),
              },
              {
                xtype: 'checkbox',
                fieldLabel: t('wvision_settings_notification_newest_on_top'),
                name: 'APPLICATION.NOTIFICATION.NEWEST_ON_TOP',
                checked: this.getValue('APPLICATION.NOTIFICATION.NEWEST_ON_TOP'),
              },
              {
                xtype: 'combo',
                fieldLabel: t('wvision_settings_notification_position'),
                editable: false,
                name: 'APPLICATION.NOTIFICATION.POSITION',
                value: this.getValue('APPLICATION.NOTIFICATION.POSITION'),
                store: [
                  ['toast-top-left', t('wvision_settings_notification_position_top_left')],
                  ['toast-top-right', t('wvision_settings_notification_position_top_right')],
                  ['toast-top-center', t('wvision_settings_notification_position_top_center')],
                  ['toast-top-full-width', t('wvision_settings_notification_position_top_full_width')],
                  ['toast-bottom-left', t('wvision_settings_notification_position_bottom_left')],
                  ['toast-bottom-right', t('wvision_settings_notification_position_bottom_right')],
                  ['toast-bottom-center', t('wvision_settings_notification_position_bottom_center')],
                  ['toast-bottom-full-width', t('wvision_settings_notification_position_bottom_full_width')],
                ],
                mode: 'local',
                triggerAction: 'all',
              },
              {
                xtype: 'combo',
                fieldLabel: t('wvision_settings_notification_show_easing'),
                editable: false,
                name: 'APPLICATION.NOTIFICATION.SHOW_EASING',
                value: this.getValue('APPLICATION.NOTIFICATION.SHOW_EASING'),
                store: [
                  ['swing', 'swing'],
                  ['linear', 'linear'],
                ],
                mode: 'local',
                triggerAction: 'all',
              },
              {
                xtype: 'combo',
                fieldLabel: t('wvision_settings_notification_hide_easing'),
                editable: false,
                name: 'APPLICATION.NOTIFICATION.HIDE_EASING',
                value: this.getValue('APPLICATION.NOTIFICATION.HIDE_EASING'),
                store: [
                  ['swing', 'swing'],
                  ['linear', 'linear'],
                ],
                mode: 'local',
                triggerAction: 'all',
              },
              {
                xtype: 'combo',
                fieldLabel: t('wvision_settings_notification_show_method'),
                editable: false,
                name: 'APPLICATION.NOTIFICATION.SHOW_METHOD',
                value: this.getValue('APPLICATION.NOTIFICATION.SHOW_METHOD'),
                store: [
                  ['fadeIn', 'fadeIn'],
                  ['slideDown', 'slideDown'],
                  ['show', 'show'],
                ],
                mode: 'local',
                triggerAction: 'all',
              },
              {
                xtype: 'combo',
                fieldLabel: t('wvision_settings_notification_hide_method'),
                editable: false,
                name: 'APPLICATION.NOTIFICATION.HIDE_METHOD',
                value: this.getValue('APPLICATION.NOTIFICATION.HIDE_METHOD'),
                store: [
                  ['fadeOut', 'fadeOut'],
                  ['slideUp', 'slideUp'],
                  ['hide', 'hide'],
                ],
                mode: 'local',
                triggerAction: 'all',
              },
              {
                xtype: 'numberfield',
                fieldLabel: t('wvision_settings_notification_show_duration'),
                name: 'APPLICATION.NOTIFICATION.SHOW_DURATION',
                value: this.getValue('APPLICATION.NOTIFICATION.SHOW_DURATION'),
              },
              {
                xtype: 'numberfield',
                fieldLabel: t('wvision_settings_notification_hide_duration'),
                name: 'APPLICATION.NOTIFICATION.HIDE_DURATION',
                value: this.getValue('APPLICATION.NOTIFICATION.HIDE_DURATION'),
              },
              {
                xtype: 'numberfield',
                fieldLabel: t('wvision_settings_notification_timeout'),
                name: 'APPLICATION.NOTIFICATION.TIMEOUT',
                value: this.getValue('APPLICATION.NOTIFICATION.TIMEOUT'),
              },
              {
                xtype: 'numberfield',
                fieldLabel: t('wvision_settings_notification_extended_timeout'),
                name: 'APPLICATION.NOTIFICATION.EXTENDED_TIMEOUT',
                value: this.getValue('APPLICATION.NOTIFICATION.EXTENDED_TIMEOUT'),
              },
            ],
          },
          this.getMultiSiteSmtpSettings()
        ],
      });

      this.layout.add(this.settingsPanel);

      this.panel.add(this.layout);

      pimcore.layout.refresh();
    }

    return this.panel;
  },

  getMultiSiteSmtpSettings: function () {
    var me = this;
    var tabPanel = Ext.create({
      xtype: 'tabpanel'
    });
    var fieldset = Ext.create({
      xtype: 'fieldset',
      title: t('wvision_settings_multi_site_smtp'),
      collapsible: true,
      collapsed: true,
      autoHeight: true,
      defaultType: 'textfield',
      defaults: {width: 600},
      items : [tabPanel]
    });

    pimcore.globalmanager.get('sites').load(function() {
      Ext.each(pimcore.globalmanager.get('sites').getRange(), function(site) {
        var siteId = site.get("id");

        if (siteId === "default") {
          return;
        }

        tabPanel.add({
          title: site.get("domain"),
          layout: 'form',
          defaultType: 'textfield',
          defaults: { width: 600 },
          items: [
            {
              fieldLabel: t("email_smtp_host"),
              name: "APPLICATION.MULTISITE."+siteId+".SMTP.HOST",
              value: me.getValue("APPLICATION.MULTISITE."+siteId+".SMTP.HOST")
            },
            {
              fieldLabel: t("email_smtp_ssl"),
              xtype: "combo",
              width: 425,
              name: "APPLICATION.MULTISITE."+siteId+".SMTP.SSL",
              value: me.getValue("APPLICATION.MULTISITE."+siteId+".SMTP.SSL"),
              store: [
                ["", t('no_ssl')],
                ["tls", "TLS"],
                ["ssl", "SSL"]
              ],
              mode: "local",
              editable: false,
              forceSelection: true,
              triggerAction: "all"
            },
            {
              fieldLabel: t("email_smtp_port"),
              name: "APPLICATION.MULTISITE."+siteId+".SMTP.PORT",
              value: me.getValue("APPLICATION.MULTISITE."+siteId+".SMTP.PORT")
            },
            {
              fieldLabel: t("email_smtp_name"),
              name: "APPLICATION.MULTISITE."+siteId+".SMTP.NAME",
              value: me.getValue("APPLICATION.MULTISITE."+siteId+".SMTP.NAME")
            },
            {
              fieldLabel: t("email_smtp_auth_method"),
              xtype: "combo",
              width: 425,
              name: "APPLICATION.MULTISITE."+siteId+".SMTP.AUTH.METHOD",
              value: me.getValue("APPLICATION.MULTISITE."+siteId+".SMTP.AUTH.METHOD"),
              store: [
                ["", t('no_authentication')],
                ["login", "LOGIN"],
                ["plain", "PLAIN"],
                ["crammd5", "CRAM-MD5"]
              ],
              mode: "local",
              editable: false,
              forceSelection: true,
              triggerAction: "all",
              listeners: {
                select: me.smtpAuthSelected.bind(this, "email" + siteId)
              }
            },
            {
              fieldLabel: t("email_smtp_auth_username"),
              name: "APPLICATION.MULTISITE."+siteId+".SMTP.AUTH.USERNAME",
              itemId: "email"+siteId+"_username",
              hidden: (me.getValue("APPLICATION.MULTISITE."+siteId+".SMTP.AUTH.METHOD").length > 1) ? false : true,
              value: me.getValue("APPLICATION.MULTISITE."+siteId+".SMTP.AUTH.METHOD")
            },
            {
              fieldLabel: t("email_smtp_auth_password"),
              name: "APPLICATION.MULTISITE."+siteId+".SMTP.AUTH.PASSWORD",
              inputType: "password",
              itemId: "email"+siteId+"_password",
              hidden: (me.getValue("APPLICATION.MULTISITE."+siteId+".SMTP.AUTH.METHOD").length > 1) ? false : true,
              value: me.getValue("APPLICATION.MULTISITE."+siteId+".SMTP.AUTH.METHOD")
            }
          ]
        });
      });
    });

    return fieldset;;
  },

  smtpAuthSelected: function (type, combo) {

    var username = combo.ownerCt.getComponent(type + "_username");
    var pass = combo.ownerCt.getComponent(type + "_password");

    if (!combo.getValue()) {
      username.hide();
      pass.hide();
      username.setValue("");
      pass.setValue("");
    } else {
      username.show();
      pass.show();
    }
  },

  activate: function () {
    var tabPanel = Ext.getCmp('pimcore_panel_tabs');
    tabPanel.setActiveItem('wvision_settings');
  },

  save: function () {
    var values = {};

    var data = this.settingsPanel.getForm().getFieldValues();

    Ext.Ajax.request({
      url: '/admin/wvision/settings/set',
      method: 'post',
      params: {
        settings: Ext.encode(data),
      },
      success: function (response) {
        try {
          var res = Ext.decode(response.responseText);
          if (res.success) {
            pimcore.helpers.showNotification(t('success'), t('success'), 'success');

            Ext.MessageBox.confirm(t('info'), t('reload_pimcore_changes'), function (buttonValue) {
              if (buttonValue == 'yes') {
                window.location.reload();
              }
            }.bind(this));

          } else {
            pimcore.helpers.showNotification(t('error'), t('error'),
              'error', t(res.message));
          }
        } catch (e) {
          pimcore.helpers.showNotification(t('error'), t('error'), 'error');
        }
      },
    });
  },
});