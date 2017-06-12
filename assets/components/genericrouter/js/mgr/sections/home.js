Ext.onReady(function() {
    MODx.load({ xtype: 'genericrouter-page-home'});
});
GenericRouter.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'genericrouter-panel-home'
            ,renderTo: 'genericrouter-panel-home-div'
        }]
    });
    GenericRouter.page.Home.superclass.constructor.call(this, config);
};

Ext.extend(GenericRouter.page.Home, MODx.Component);
Ext.reg('genericrouter-page-home', GenericRouter.page.Home);
