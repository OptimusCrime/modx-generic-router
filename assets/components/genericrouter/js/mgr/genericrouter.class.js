var GenericRouter = function(config) {
    config = config || {};
    GenericRouter.superclass.constructor.call(this, config);
};
Ext.extend(GenericRouter, Ext.Component, {
    page: {}
    ,window: {}
    ,grid: {}
    ,tree: {}
    ,panel: {}
    ,combo: {}
    ,config: {}
});
Ext.reg('genericrouter', GenericRouter);

GenericRouter = new GenericRouter();
