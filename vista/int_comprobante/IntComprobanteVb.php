<?php
/**
*@package pXP
*@file gen-SistemaDist.php
*@author  (fprudencio)
*@date 20-09-2011 10:22:05
*@description Archivo con la interfaz de usuario que permite 
*dar el visto a solicitudes de compra
*
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.IntComprobanteVb = {
    bedit: false,
    bnew: false,
    bsave: false,
    bdel: true,
	require: '../../../sis_contabilidad/vista/int_comprobante/IntComprobante.php',
	requireclase: 'Phx.vista.IntComprobante',
	title: 'Libro Diario',
	nombreVista: 'IntComprobanteVb',
	
	constructor: function(config) {
		
	    var me = this;
	
		console.log('....................',config)
		
		
        me.bMedios = [];
        me.addButtonCustom(config.idContenedor,'ant_estado', { argument: { estado: 'anterior'}, text:'Rechazar',iconCls: 'batras', disabled: true, handler: this.antEstado, tooltip: '<b>Pasar al Anterior Estado</b>' });
        me.addButtonCustom(config.idContenedor, 'sig_estado', { text: 'Aprobar', iconCls: 'badelante', disabled: true, handler: this.sigEstado, tooltip: '<b>Pasar al Siguiente Estado</b>' });
        
        Phx.vista.IntComprobanteVb.superclass.constructor.call(me,config);
        
        
        //this.addButtonCustom('medio', 'sig_estado', { text: 'Aprobar', iconCls: 'badelante', disabled: true, handler: this.sigEstado, tooltip: '<b>Pasar al Siguiente Estado</b>' });
        
	    this.addButton('chkdep', {	text:'Dependencias',
				iconCls:  'blist',
				disabled: true,
				handler:  this.checkDependencias,
				tooltip: '<b>Revisar Dependencias </b><p>Revisar dependencias del comprobante</p>'
			});
			
		this.init();	
	},
    
    south:{
	  url:'../../../sis_contabilidad/vista/int_transaccion/IntTransaccionLd.php',
	  title:'Transacciones', 
	  height:'50%',	//altura de la ventana hijo
	  cls:'IntTransaccionLd'
	},
	preparaMenu : function(n) {
			var tb = Phx.vista.IntComprobanteVb.superclass.preparaMenu.call(this);
			this.getBoton('btnImprimir').enable();
			this.getBoton('btnRelDev').enable();
			this.getBoton('btnDocCmpVnt').enable();
			this.getBoton('chkpresupuesto').enable();
			
			this.getBoton('chkdep').enable();
			this.getBoton('btnChequeoDocumentosWf').enable();
            this.getBoton('diagrama_gantt').enable();
            this.getBoton('btnObs').enable();
            
            
            this.getBoton('ant_estado').enable();
            this.getBoton('sig_estado').enable();
			
			return tb;
	},
	liberaMenu : function() {
			var tb = Phx.vista.IntComprobanteVb.superclass.liberaMenu.call(this);
			this.getBoton('btnImprimir').disable();
			this.getBoton('btnRelDev').disable();
			this.getBoton('btnDocCmpVnt').disable();
			this.getBoton('chkpresupuesto').disable();
			
			this.getBoton('chkdep').disable();
			this.getBoton('btnChequeoDocumentosWf').disable();
            this.getBoton('diagrama_gantt').disable();
            this.getBoton('btnObs').disable() 
            
            this.getBoton('ant_estado').disable();
            this.getBoton('sig_estado').disable();
			
	},
	checkDependencias: function(){                   
			  var rec=this.sm.getSelected();
			  var configExtra = [];
			  this.objChkPres = Phx.CP.loadWindows('../../../sis_contabilidad/vista/int_comprobante/CbteDependencias.php',
										'Dependencias',
										{
											modal:true,
											width: '80%',
											height: '80%'
										}, 
										rec.data, 
										this.idContenedor,
										'CbteDependencias');
			   
	},
	//para retroceder de estado
    antEstado:function(res){
         var rec=this.sm.getSelected(),
             obsValorInicial;
         Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/AntFormEstadoWf.php',
            'Estado de Wf',
            {   modal: true,
                width: 450,
                height: 250
            }, 
            {    data: rec.data, 
            	 estado_destino: res.argument.estado,
                 obsValorInicial: obsValorInicial }, this.idContenedor,'AntFormEstadoWf',
            {
                config:[{
                          event:'beforesave',
                          delegate: this.onAntEstado,
                        }
                        ],
               scope:this
           });
   },
   
   onAntEstado: function(wizard,resp){
            Phx.CP.loadingShow();
            var operacion = 'cambiar';
            operacion=  resp.estado_destino == 'inicio'?'inicio':operacion; 
            Ext.Ajax.request({
                url:'../../sis_contabilidad/control/IntComprobante/anteriorEstado',
                params:{
                        id_proceso_wf: resp.id_proceso_wf,
                        id_estado_wf:  resp.id_estado_wf,  
                        obs: resp.obs,
                        operacion: operacion,
                        id_int_comprobante: resp.data.id_int_comprobante
                 },
                argument: { wizard: wizard },  
                success: this.successEstadoSinc,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
           
    },
     successEstadoSinc:function(resp){
        Phx.CP.loadingHide();
        resp.argument.wizard.panel.destroy()
        this.reload();
     },
     
     sigEstado:function(){                   
      	var rec=this.sm.getSelected();
      	
      	this.mostrarWizard(rec);
      	
               
     },
     
    mostrarWizard : function(rec) {
     	var configExtra = [],
     		obsValorInicial;
     	   
		this.objWizard = Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/FormEstadoWf.php',
                                'Estado de Wf',
                                {
                                    modal: true,
                                    width: 700,
                                    height: 450
                                }, 
                                {
                                	configExtra: configExtra,
                                	eventosExtra: this.eventosExtra,
                                	data:{
                                       id_estado_wf: rec.data.id_estado_wf,
                                       id_proceso_wf: rec.data.id_proceso_wf,
                                       id_int_comprobante: rec.data.id_int_comprobante,
                                       fecha_ini: rec.data.fecha
                                   },
                                   obsValorInicial: obsValorInicial,
                                }, this.idContenedor, 'FormEstadoWf',
                                {
                                    config:[{
                                              event:'beforesave',
                                              delegate: this.onSaveWizard,
                                              
                                            },
					                        {
					                          event:'requirefields',
					                          delegate: function () {
						                          	this.onButtonEdit();
										        	this.window.setTitle('Registre los campos antes de pasar al siguiente estado');
										        	this.formulario_wizard = 'si';
					                          }
					                          
					                        }],
                                  
                                    scope:this
                        });        
     },
    onSaveWizard:function(wizard,resp){
        Phx.CP.loadingShow();
        Ext.Ajax.request({
            url:'../../sis_contabilidad/control/IntComprobante/siguienteEstado',
            params:{
            	    id_int_comprobante: wizard.data.id_int_comprobante,
            	    id_proceso_wf_act:  resp.id_proceso_wf_act,
	                id_estado_wf_act:   resp.id_estado_wf_act,
	                id_tipo_estado:     resp.id_tipo_estado,
	                id_funcionario_wf:  resp.id_funcionario_wf,
	                id_depto_wf:        resp.id_depto_wf,
	                obs:                resp.obs,
	                instruc_rpc:		resp.instruc_rpc,
	                json_procesos:      Ext.util.JSON.encode(resp.procesos)
	                
                },
            success: this.successWizard,
            failure: this.conexionFailure, 
            argument: { wizard:wizard },
            timeout: this.timeout,
            scope: this
        });
    },
    successWizard: function(resp){
        Phx.CP.loadingHide();
        resp.argument.wizard.panel.destroy()
        this.reload();
    }
};
</script>