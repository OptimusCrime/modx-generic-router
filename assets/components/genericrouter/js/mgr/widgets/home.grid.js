GenericRouter.grid.GenericRouter = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'genericrouter-grid-genericrouter'
        ,url: GenericRouter.config.connectorUrl
        ,baseParams: {
            action: 'mgr/genericrouter/getList'
        }
        ,fields: [
            'id'
            ,'expression'
            ,'target'
            ,'name'
            ,'priority'
            ,'enabled'
            ,'mode'
        ]
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,anchor: '97%'
        ,autoExpandColumn: 'comment'
        ,columns: [{
            dataIndex: 'expression'
            ,header: _('genericrouter.expression')
            ,width: 100
        }, {
            dataIndex: 'target'
            ,header: _('genericrouter.target')
            ,sortable: false
            ,width: 30
        }, {
            dataIndex: 'name'
            ,header: _('genericrouter.name_attribute')
            ,sortable: true
            ,width: 80
        }, {
            dataIndex: 'priority'
            ,header: _('genericrouter.priority')
            ,sortable: true
            ,width: 80
        }, {
            dataIndex: 'mode'
            ,header: _('genericrouter.mode')
            ,sortable: true
            ,width: 80
        }]
        ,tbar: [{
            text: _('genericrouter.route_new')
            ,handler: {
                xtype: 'genericrouter-window-genericrouter-create'
                ,blankValues: true
            }
        }, {
            text: _('genericrouter.route_recreate')
            ,handler: this.routeRecreate
        }]
    });
    GenericRouter.grid.GenericRouter.superclass.constructor.call(this, config)
};
Ext.extend(GenericRouter.grid.GenericRouter, MODx.grid.Grid, {
    getMenu: function() {
        return [{
            text: _('genericrouter.route_edit')
            ,handler: this.routeEdit
        },'-',{
            text: _('genericrouter.route_delete')
            ,handler: this.routeDelete
        }];
    }

    ,routeRecreate: function(btn, e) {
        MODx.msg.confirm({
            title: _('genericrouter.route_recreate')
            ,text: _('genericrouter.route_recreate_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/genericrouter/recreate'
            }
            ,listeners: {
                'success': {
                    fn: this.refresh
                    ,scope: this
                }
            }
        });
    }

    ,routeEdit: function(btn, e) {
        if (!this.updateRuleWindow) {
            this.updateRuleWindow = MODx.load({
                xtype: 'genericrouter-window-genericrouter-update'
                ,record: this.menu.record
                ,listeners: {
                    'success': {
                        fn: this.refresh
                        ,scope: this
                    }
                }
            });
        }
        this.updateRuleWindow.setValues(this.menu.record);
        this.updateRuleWindow.show(e.target);
    }

    ,routeDelete: function() {
        MODx.msg.confirm({
            title: _('genericrouter.route_delete')
            ,text: _('genericrouter.route_delete_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/genericrouter/delete'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {
                    fn: this.refresh
                    ,scope: this
                }
            }
        });
    }
});
Ext.reg('genericrouter-grid-genericrouter', GenericRouter.grid.GenericRouter);

GenericRouter.window.CreateRule = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        title: _('genericrouter.rule_new')
        ,url: GenericRouter.config.connectorUrl
        ,baseParams: {
            action: 'mgr/genericrouter/create'
        }
        ,fields: [{
            border: false
            ,layout: 'column'
            ,defaults: {
                anchor: '100%'
                ,labelAlign: 'top'
                ,layout: 'form'
                ,border: false
            }
            ,items: [{
                columnWidth: 1
                ,items: [{
                    allowBlank: false
                    ,anchor: '100%'
                    ,fieldLabel: _('genericrouter.resource')
                    ,name: 'resource'
                    ,xtype: 'genericrouter-combo-resources'
                },{
                    xtype: 'checkbox'
                    ,boxLabel: _('genericrouter.children')
                    ,labelStyle: 'font-weight: bold; color: #777777;'
                    ,name: 'children'
                    ,checked: true
                }]
            }]
        },{
            border: false
            ,layout: 'column'
            ,defaults: {
                anchor: '100%'
                ,labelAlign: 'top'
                ,layout: 'form'
                ,border: false
            }
            ,items: [{
                columnWidth: 1
                ,items: [{
                    html: '<div style="height: 1px; border-top: 1px solid #C0C0C0; width: 100%; display: block; margin-top: 10px; margin-bottom: 10px;"></div>'
                }]
            }]
        },{
            border: false
            ,layout: 'column'
            ,defaults: {
                anchor: '100%'
                ,labelAlign: 'top'
                ,layout: 'form'
                ,border: false
            }
            ,items: [{
                columnWidth: .25
                ,items: [{
                    xtype: 'checkbox'
                    ,anchor: '25%'
                    ,name: 'on_create'
                    ,fieldLabel: _('genericrouter.create')
                    ,checked: true
                }]
            },{
                columnWidth: .25
                ,items: [{
                    xtype: 'checkbox'
                    ,anchor: '25%'
                    ,name: 'on_update'
                    ,fieldLabel: _('genericrouter.update')
                    ,checked: true
                }]
            },{
                columnWidth: .25
                ,items: [{
                    xtype: 'checkbox'
                    ,anchor: '25%'
                    ,name: 'on_sort'
                    ,fieldLabel: _('genericrouter.sort')
                    ,checked: true
                }]
            },{
                columnWidth: .25
                ,items: [{
                    xtype: 'checkbox'
                    ,anchor: '25%'
                    ,name: 'on_delete'
                    ,fieldLabel: _('genericrouter.delete')
                    ,checked: true
                }]
            }]
        },{
            border: false
            ,layout: 'column'
            ,defaults: {
                anchor: '100%'
                ,labelAlign: 'top'
                ,layout: 'form'
                ,border: false
            }
            ,items: [{
                columnWidth: 1
                ,items: [{
                    html: '<div style="height: 1px; border-top: 1px solid #C0C0C0; width: 100%; display: block; margin-bottom: 10px;"></div>'
                }]
            }]
        },{
            border: false
            ,layout: 'column'
            ,defaults: {
                anchor: '100%'
                ,labelAlign: 'top'
                ,layout: 'form'
                ,border: false
            }
            ,items: [{
                columnWidth: 1
                ,items: [{
                    allowBlank: false
                    ,anchor: '100%'
                    ,fieldLabel: _('genericrouter.clear_cache')
                    ,name: 'clear_cache'
                    ,xtype: 'textfield'
                },{
                    html: '<p style="text-align: justify">Enter ids for resources that should have their cache removed here. Separate each resource with a comma.</p><br /><p>Adding c after the id indicates that the direct children also should have their cache deleted. A small s is a reference to self and will delete the cache for that resource.</p><br /><p>Content could be: <b>s,1,22,98c</b></p>'
                }]
            }]
        }]
    });
    GenericRouter.window.CreateRule.superclass.constructor.call(this, config);
};
Ext.extend(GenericRouter.window.CreateRule, MODx.Window);
Ext.reg('genericrouter-window-genericrouter-create', GenericRouter.window.CreateRule);

GenericRouter.window.UpdateRule = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('genericrouter.rule_update')
        ,url: GenericRouter.config.connectorUrl
        ,baseParams: {
            action: 'mgr/genericrouter/update'
        }
        ,fields: [{
            border: false
            ,layout: 'column'
            ,defaults: {
                anchor: '100%'
                ,labelAlign: 'top'
                ,layout: 'form'
                ,border: false
            }
            ,items: [{
                columnWidth: 1
                ,items: [{
                    allowBlank: false
                    ,anchor: '100%'
                    ,fieldLabel: _('genericrouter.resource')
                    ,name: 'resource'
                    ,xtype: 'genericrouter-combo-resources'
                },{
                    xtype: 'checkbox'
                    ,boxLabel: _('genericrouter.children')
                    ,labelStyle: 'font-weight: bold; color: #777777;'
                    ,name: 'children'
                },{
                    allowBlank: false
                    ,anchor: '100%'
                    ,fieldLabel: _('genericrouter.id')
                    ,name: 'id'
                    ,xtype: 'textfield'
                    ,hidden: true
                }]
            }]
        },{
            border: false
            ,layout: 'column'
            ,defaults: {
                anchor: '100%'
                ,labelAlign: 'top'
                ,layout: 'form'
                ,border: false
            }
            ,items: [{
                columnWidth: 1
                ,items: [{
                    html: '<div style="height: 1px; border-top: 1px solid #C0C0C0; width: 100%; display: block; margin-top: 10px; margin-bottom: 10px;"></div>'
                }]
            }]
        },{
            border: false
            ,layout: 'column'
            ,defaults: {
                anchor: '100%'
                ,labelAlign: 'top'
                ,layout: 'form'
                ,border: false
            }
            ,items: [{
                columnWidth: .25
                ,items: [{
                    xtype: 'checkbox'
                    ,anchor: '25%'
                    ,name: 'on_create'
                    ,fieldLabel: _('genericrouter.create')
                }]
            },{
                columnWidth: .25
                ,items: [{
                    xtype: 'checkbox'
                    ,anchor: '25%'
                    ,name: 'on_update'
                    ,fieldLabel: _('genericrouter.update')
                }]
            },{
                columnWidth: .25
                ,items: [{
                    xtype: 'checkbox'
                    ,anchor: '25%'
                    ,name: 'on_sort'
                    ,fieldLabel: _('genericrouter.sort')
                }]
            },{
                columnWidth: .25
                ,items: [{
                    xtype: 'checkbox'
                    ,anchor: '25%'
                    ,name: 'on_delete'
                    ,fieldLabel: _('genericrouter.delete')
                }]
            }]
        },{
            border: false
            ,layout: 'column'
            ,defaults: {
                anchor: '100%'
                ,labelAlign: 'top'
                ,layout: 'form'
                ,border: false
            }
            ,items: [{
                columnWidth: 1
                ,items: [{
                    html: '<div style="height: 1px; border-top: 1px solid #C0C0C0; width: 100%; display: block; margin-bottom: 10px;"></div>'
                }]
            }]
        },{
            border: false
            ,layout: 'column'
            ,defaults: {
                anchor: '100%'
                ,labelAlign: 'top'
                ,layout: 'form'
                ,border: false
            }
            ,items: [{
                columnWidth: 1
                ,items: [{
                    allowBlank: false
                    ,anchor: '100%'
                    ,fieldLabel: _('genericrouter.clear_cache')
                    ,name: 'clear_cache'
                    ,xtype: 'textfield'
                },{
                    html: '<p style="text-align: justify">Enter ids for resources that should have their cache removed here. Separate each resource with a comma.</p><br /><p>Adding c after the id indicates that the direct children also should have their cache deleted. A small s is a reference to self and will delete the cache for that resource.</p><br /><p>Content could be: <b>s,1,22,98c</b></p>'
                }]
            }]
        }]
    });
    GenericRouter.window.UpdateRule.superclass.constructor.call(this, config);
};
Ext.extend(GenericRouter.window.UpdateRule, MODx.Window);
Ext.reg('genericrouter-window-genericrouter-update', GenericRouter.window.UpdateRule);

GenericRouter.combo.Resources = function(config){
    config = config || {};
    Ext.applyIf(config,{
        baseParams:{
            action: 'mgr/genericrouter/getTree'
        }
        ,defaultValue: 0
        ,displayField: 'pagetitle'
        ,valueField: 'id'
        ,fields: ['id','pagetitle']
        ,url: GenericRouter.config.connectorUrl
    });

    config.hiddenName = config.name || '';
    config.baseParams.parent = config.parent || 0;

    GenericRouter.combo.Resources.superclass.constructor.call(this, config);
};
Ext.extend(GenericRouter.combo.Resources, MODx.combo.ComboBox);
Ext.reg('genericrouter-combo-resources', GenericRouter.combo.Resources);
