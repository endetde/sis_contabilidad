<?php
/**
*@package pXP
*@file gen-RelacionContable.php
*@author  (admin)
*@date 16-05-2013 21:52:14
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.RelacionContable=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
		this.nombre_tabla = config.nombre_tabla;
		this.tabla_id = config.tabla_id;
		//llama al constructor de la clase padre
		Phx.vista.RelacionContable.superclass.constructor.call(this,config);
		this.init();
		this.iniciarEventos();
		
	},
	tam_pag:50,
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_relacion_contable'
			},
			type:'Field',
			form:true 
		},
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'nombre_tabla'
			},
			type:'Field',
			form:true 
		},
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_tabla'
			},
			type:'Field',
			form:true 
		},
		{
	   			config:{
	   				name : 'id_gestion',
	   				origen : 'GESTION',
	   				fieldLabel : 'Gestion',
	   				allowBlank : false,
	   				gdisplayField : 'gestion',//mapea al store del grid
	   				gwidth : 100,
		   			renderer : function (value, p, record){return String.format('{0}', record.data['gestion']);}
	       	     },
	   			type : 'ComboRec',
	   			id_grupo : 0,
	   			filters : {	
			        pfiltro : 'ges.gestion',
					type : 'numeric'
				},
	   		   
	   			grid : true,
	   			form : true
	   	},
		
		{
            config:{
                name: 'id_centro_costo',
                fieldLabel: 'Centro Costo',
                allowBlank: false,
                tinit:false,
                origen:'CENTROCOSTO',
                gdisplayField: 'codigo_cc',
                anchor: '80%',
                gwidth: 300
            },
            type:'ComboRec',
            filters:{pfiltro:'cc.codigo_cc',type:'string'},
            id_grupo:1,
            grid:true,
            form:true
        },
		
		{
	   			config:{
	   				sysorigen:'sis_contabilidad',
	       		    name:'id_cuenta',
	   				origen:'CUENTA',
	   				allowBlank:false,
	   				fieldLabel:'Cuenta',
	   				gdisplayField:'nombre_cuenta',//mapea al store del grid
	   				gwidth:200,
		   			 renderer:function (value, p, record){return String.format('{0}',record.data['nro_cuenta'] + '-' + record.data['nombre_cuenta']);}
	       	     },
	   			type:'ComboRec',
	   			id_grupo:0,
	   			filters:{	
			        pfiltro:'cue.nombre_cuenta#cu.nro_cuenta',
					type:'string'
				},
	   		   
	   			grid:true,
	   			form:true
	   	},
		{
	   			config:{
	   				sysorigen:'sis_contabilidad',
	       		    name:'id_auxiliar',
	   				origen:'AUXILIAR',
	   				allowBlank:false,
	   				fieldLabel:'Auxiliar',
	   				gdisplayField:'nombre_auxiliar',//mapea al store del grid
	   				gwidth:200,
		   			 renderer:function (value, p, record){return String.format('{0}',record.data['codigo_auxiliar'] + '-' + record.data['nombre_auxiliar']);}
	       	     },
	   			type:'ComboRec',
	   			id_grupo:0,
	   			filters:{	
			        pfiltro:'au.codigo_auxiliar#au.nombre_auxiliar',
					type:'string'
				},
	   		   
	   			grid:true,
	   			form:true
	   	},
		{
	   			config:{
	   				sysorigen:'sis_presupuestos',
	       		    name:'id_partida',
	   				origen:'PARTIDA',
	   				allowBlank:true,
	   				fieldLabel:'Partida',
	   				gdisplayField:'nombre_partida',//mapea al store del grid
	   				gwidth:200,
		   			 renderer:function (value, p, record){return String.format('{0}',record.data['codigo_partida'] + '-' + record.data['nombre_partida']);}
	       	     },
	   			type:'ComboRec',
	   			id_grupo:0,
	   			filters:{	
			        pfiltro:'par.codigo#par.nombre_partida',
					type:'string'
				},
	   		   
	   			grid:true,
	   			form:true
	   	},	
		
		{
			config:{
				name: 'estado_reg',
				fieldLabel: 'Estado Reg.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:10
			},
			type:'TextField',
			filters:{pfiltro:'relcon.estado_reg',type:'string'},
			id_grupo:1,
			grid:true,
			form:false
		},
		{
			config:{
				name: 'fecha_reg',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
						format: 'd/m/Y', 
						renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
			type:'DateField',
			filters:{pfiltro:'relcon.fecha_reg',type:'date'},
			id_grupo:1,
			grid:true,
			form:false
		},
		{
			config:{
				name: 'usr_reg',
				fieldLabel: 'Creado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
			type:'NumberField',
			filters:{pfiltro:'usu1.cuenta',type:'string'},
			id_grupo:1,
			grid:true,
			form:false
		},
		{
			config:{
				name: 'fecha_mod',
				fieldLabel: 'Fecha Modif.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
						format: 'd/m/Y', 
						renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
			type:'DateField',
			filters:{pfiltro:'relcon.fecha_mod',type:'date'},
			id_grupo:1,
			grid:true,
			form:false
		},
		{
			config:{
				name: 'usr_mod',
				fieldLabel: 'Modificado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
			type:'NumberField',
			filters:{pfiltro:'usu2.cuenta',type:'string'},
			id_grupo:1,
			grid:true,
			form:false
		}
	],
	
	title:'Relación Contable',
	ActSave:'../../sis_contabilidad/control/RelacionContable/insertarRelacionContable',
	ActDel:'../../sis_contabilidad/control/RelacionContable/eliminarRelacionContable',
	ActList:'../../sis_contabilidad/control/RelacionContable/listarRelacionContable',
	id_store:'id_relacion_contable',
	fields: [
		{name:'id_relacion_contable', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'id_tipo_relacion_contable', type: 'numeric'},
		{name:'nombre_tipo_relacion', type: 'string'},
		{name:'id_cuenta', type: 'numeric'},
		{name:'id_partida', type: 'numeric'},
		{name:'id_gestion', type: 'numeric'},
		{name:'gestion', type: 'numeric'},
		{name:'id_auxiliar', type: 'numeric'},
		{name:'id_tabla', type: 'numeric'},
		{name:'id_centro_costo', type: 'numeric'},
		{name:'codigo_cc', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'nro_cuenta', type: 'string'},
		{name:'nombre_cuenta', type: 'string'},
		{name:'codigo_auxiliar', type: 'string'},
		{name:'nombre_auxiliar', type: 'string'},
		{name:'codigo_partida', type: 'string'},
		{name:'tiene_centro_costo', type: 'string'},
		{name:'tiene_partida', type: 'string'},
		{name:'tiene_auxiliar', type: 'string'},
		{name:'nombre_partida', type: 'string'}
		
	],
	sortInfo:{
		field: 'id_relacion_contable',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true	
})
</script>
		
		