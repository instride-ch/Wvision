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

pimcore.registerNS('pimcore.plugin.wvision.settings');
pimcore.document.newsletters.sendingPanel = Class.create(pimcore.document.newsletters.sendingPanel, {
    send: function() {

        Ext.MessageBox.confirm(t("are_you_sure"), t("do_you_really_want_to_send_the_newsletter_to_all_recipients"), function (buttonValue) {

            if (buttonValue == "yes") {
                var fieldValues = this.layout.getForm().getFieldValues();

                var params = {
                    id: this.document.id,
                    adapterParams: Ext.encode(this.currentSourceAdapter.getValues()),
                    addressAdapterName: this.currentSourceAdapter.getName()
                };

                Ext.Ajax.request({
                    url: "/plugin/Wvision/admin_newsletter/send",
                    method: "post",
                    params: params,
                    success: function (response) {
                        this.checkForActiveSendingProcess();

                        var res = Ext.decode(response.responseText);

                        if (res.success) {
                            Ext.MessageBox.alert(t("info"), t("newsletter_sent_message"))
                        } else {
                            Ext.MessageBox.alert(t("error"), t("newsletter_send_error"))
                        }

                        //again check in 2 seconds since it may take a while until process starts
                        window.setTimeout(function() {
                            this.checkForActiveSendingProcess();
                        }.bind(this), 2000);

                    }.bind(this)
                });
            }

        }.bind(this));

    },

    sendTest: function() {

        var fieldValues = this.layout.getForm().getFieldValues();

        var params = {
            id: this.document.id,
            adapterParams: Ext.encode(this.currentSourceAdapter.getValues()),
            addressAdapterName: this.currentSourceAdapter.getName(),
            testMailAddress: fieldValues.to
        };

        Ext.Ajax.request({
            url: "/plugin/Wvision/admin_newsletter/send-test",
            method: "post",
            params: params,
            success: function(response) {
                var res = Ext.decode(response.responseText);

                if(res.success) {
                    Ext.MessageBox.alert(t("info"), t("newsletter_test_sent_message"))
                } else {
                    Ext.MessageBox.alert(t("error"), t("newsletter_send_error"))
                }
            }
        });

    },
});