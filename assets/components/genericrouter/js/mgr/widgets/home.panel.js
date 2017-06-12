GenericRouter.panel.Home = function(config) {
    config = config || {};
    Ext.apply(config, {
        border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,items: [{
            html: '<h2>' + _('genericrouter') + '</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        }, {
            xtype: 'modx-tabs'
            ,defaults: {
                border: false
                ,autoHeight: true
            }
            ,border: true
            ,items: [{
                title: _('genericrouter.main')
                ,defaults: {
                    autoHeight: true
                }
                ,items: [{
                    html: '<p>' + _('genericrouter.desc') + '</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                }, {
                    xtype: 'genericrouter-grid-genericrouter'
                    ,cls: 'main-wrapper'
                    ,preventRender: true
                }]
            }]
        }]
    });
    GenericRouter.panel.Home.superclass.constructor.call(this, config);
};

Ext.extend(GenericRouter.panel.Home, MODx.Panel);
Ext.reg('genericrouter-panel-home', GenericRouter.panel.Home);
