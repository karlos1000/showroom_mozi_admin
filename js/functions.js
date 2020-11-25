/*
 * Date: 5/05/2017
 * Actualizado: 11/01/2018
 * Funciones generales y especificas de javascript
 */

function ordenarCombobox(){
  //Ordenar combos box
  var arrCombos = ["id_galeria", "act_id_galeria", "modeloVersionB", "vg_modelo", "conceptoPrecioB", "conceptoPlanB",
                   "versionZonaGrid_mt_c3_filter_input", "versionPlanGrid_mt_c1_filter_input", "vp_idtipoplan",
                   "id_plan"]; 
  var options = "";
  $(arrCombos).each(function(i,v){
      // console.log(i + " - " + v);
      var options = $('select#'+v+' option');
      var arr = options.map(function(_, o) {
          return {
              t: $(o).text(),
              v: o.value,
              s: ($(o).is(':selected'))?"selected":""
          };
      }).get();
      arr.sort(function(o1, o2) {
        return o1.t > o2.t ? 1 : o1.t < o2.t ? -1 : 0;
      });

      // console.log(arr);
      // Agregar opciones
      options.each(function(i, o) {
        o.value = arr[i].v;
        $(o).text(arr[i].t);
      });
      // Limpiar la opcion seleccionada
      $("#"+v+" option[selected]").removeAttr("selected");
      
      setTimeout(function(){
        //Agregar selected a la opcion 
        options.each(function(i, o) {
          if(arr[i].s!=""){
            // console.log(o.value);
            // $(this).prop('selected', true);
            $(this).attr('selected', true);
            $(this).val(o.value);
          }        
        });
      },150);
  });
  // Fin ordenar combos box
}

$(document).ready(function(){
  //Para ordenar los combox box alfabeticamente 
  ordenarCombobox();

  $('input[name=\"activo\"]').click(function() {
    var v = $(this).val();
    var value = (v == 1) ? '0' : '1';
    $('#activo').val(value);        
  });
  
  $('input[name=\"camion\"]').click(function() {
    var v = $(this).val();
    var value = (v == 1) ? '0' : '1';
    $('#camion').val(value);        
  });

  // Si es nuevo o no
  $('input[name=\"esnuevo\"]').click(function() {
    var v = $(this).val();
    var value = (v == 1) ? '0' : '1';
    $('#esnuevo').val(value);        
  });

  // si se muestra o no el anio
  $('input[name=\"mostrarAnio\"]').click(function() {
    var v = $(this).val();
    var value = (v == 1) ? '0' : '1';
    $('#mostrarAnio').val(value);        
  });

  $('input[name=\"urlactivas_agencia\"]').click(function() {
    var v = $(this).val();
    var value = (v == 1) ? '0' : '1';
    $('#urlactivas_agencia').val(value);        
  });


  //Validar formulario registro gama de modelo
  if($("#formRegGamaModelo").length){    
    $("#formRegGamaModelo").validate({
      submitHandler: function(form) { 
          // setLoading($(form).attr('id')); 
          form.submit();
        }
      });
  }

  //Para todas las vistas que tengan fechas con kool agregar atributo readonly
	$("#fechaDel").attr("readonly", "readonly");
	$("#fechaAl").attr("readonly", "readonly");
        
  /* INICIO FANCYBOX */
  if($("#btnAgregarPlan").length){
    $("#btnAgregarPlan").fancybox({
      autoDimensions: false,
      padding : 30,
      width : 900,
      height : 900,
      autoScale : true,
      closeBtn : true,
      closeClick  : false,
      helpers : {
       overlay : {closeClick: true}
     },
     beforeLoad: function() {
     }
   });
  }

  if($(".btnEditarVersionPlan").length){
    $(".btnEditarVersionPlan").fancybox({
      autoDimensions: false,
      padding : 30,
      width : 900,
      height : 900,
      autoScale : true,
      closeBtn : true,
      closeClick  : false,
      helpers : {
       overlay : {closeClick: true}
     },
     beforeLoad: function() {
     }
   });
  }

  if($("#btnAgregarRequisito").length){
    $("#btnAgregarRequisito").fancybox({
      autoDimensions: false,
      padding : 30,
      width : 900,
      height : 900,
      autoScale : true,
      closeBtn : true,
      closeClick  : false,
      helpers : {
       overlay : {closeClick: true}
     },
     beforeLoad: function() {
     }
   });
  }

  if($(".btnEditarVersionReq").length){
    $(".btnEditarVersionReq").fancybox({
      autoDimensions: false,
      padding : 30,
      width : 900,
      height : 900,
      autoScale : true,
      closeBtn : true,
      closeClick  : false,
      helpers : {
       overlay : {closeClick: true}
     },
     beforeLoad: function() {
     }
   });
  }

  if($("#btnAgregarColor").length){
    $("#btnAgregarColor").fancybox({
      autoDimensions: false,
      padding : 30,
      width : 900,
      height : 900,
      autoScale : true,
      closeBtn : true,
      closeClick  : false,
      helpers : {
       overlay : {closeClick: true}
     },
     beforeLoad: function() {
     }
   });
  }

  if($(".btnEditarVersionColor").length){
    $(".btnEditarVersionColor").fancybox({
      autoDimensions: false,
      padding : 30,
      width : 900,
      height : 900,
      autoScale : true,
      closeBtn : true,
      closeClick  : false,
      helpers : {
       overlay : {closeClick: true}
     },
     beforeLoad: function() {
     }
   });
  }

  if($("#btnAgregarZona").length){
    $("#btnAgregarZona").fancybox({
      autoDimensions: false,
      padding : 30,
      width : 900,
      height : 900,
      autoScale : true,
      closeBtn : false,
      closeClick  : false,
      helpers : {
       overlay : {closeClick: false}
     },
     beforeLoad: function() {
     }
   });
  }

  if($(".btnEditarImgGaleria").length){
    $(".btnEditarImgGaleria").fancybox({
      autoDimensions: false,
      padding : 30,
      width : 900,
      height : 900,
      autoScale : true,
      closeBtn : false,
      closeClick  : false,
      helpers : {
       overlay : {closeClick: false}
     },
     beforeLoad: function() {
     }
   });
  }

  if($("#btnAgregarAgregarImgGal").length){
    $("#btnAgregarAgregarImgGal").fancybox({
      autoDimensions: false,
      padding : 30,
      width : 900,
      height : 900,
      autoScale : true,
      closeBtn : true,
      closeClick  : false,
      helpers : {
       overlay : {closeClick: true}
     },
     beforeLoad: function() {
     }
   });
  }

  //Mostrar todos los planes
  if($("#btnAgregarPlanPre").length){
    $("#btnAgregarPlanPre").fancybox({
      autoDimensions: false,
      padding : 30,
      width : 900,
      height : 900,
      autoScale : true,
      closeBtn : true,
      closeClick  : false,
      helpers : {
       overlay : {closeClick: true}
     },
     beforeLoad: function() {
     }
   });
  }
  if($("#btnAgregarRequisitoPre").length){
    $("#btnAgregarRequisitoPre").fancybox({
      autoDimensions: false,
      padding : 30,
      width : 900,
      height : 900,
      autoScale : true,
      closeBtn : true,
      closeClick  : false,
      helpers : {
       overlay : {closeClick: true}
     },
     beforeLoad: function() {
     }
   });
  }

  if($("#btnAgregarPromocion").length){
    $("#btnAgregarPromocion").fancybox({
      autoDimensions: false,
      padding : 30,
      width : 900,
      height : 900,
      autoScale : true,
      closeBtn : true,
      closeClick  : false,
      helpers : {
       overlay : {closeClick: true}
     },
     beforeLoad: function() {
     }
   });
  }
  
  //fancy para ordenar los modelos
  if($("#btnAgregarOrdenModelo").length){
    $("#btnAgregarOrdenModelo").fancybox({
      autoDimensions: false,
      padding : 30,
      width : 900,
      height : 900,
      autoScale : true,
      closeBtn : true,
      closeClick  : false,
      helpers : {
       overlay : {closeClick: true}
     },
     beforeLoad: function() {      
     }
   });
  }
  /* FIN FANCYBOX */


  if(typeof tieneAlertify !== "undefined")
  {
    alertify.defaults = {
        // dialogs defaults
        autoReset:true,
        basic:false,
        closable:true,
        closableByDimmer:true,
        frameless:false,
        maintainFocus:true, // <== global default not per instance, applies to all dialogs
        maximizable:true,
        modal:true,
        movable:true,
        moveBounded:false,
        overflow:true,
        padding: true,
        pinnable:true,
        pinned:true,
        preventBodyShift:false, // <== global default not per instance, applies to all dialogs
        resizable:true,
        startMaximized:false,
        transition:'pulse',
        // notifier defaults
        notifier:{
            // auto-dismiss wait time (in seconds)  
            delay:1.2,
            // default position
            position:'top-center',
            // adds a close button to notifier messages
            closeButton: false
          },
        // language resources 
        glossary:{
            // dialogs default title
            title:'',
            // ok button text
            ok: 'OK',
            // cancel button text
            cancel: 'Cancelar'            
          },
        // theme settings
        theme:{
            // class name attached to prompt dialog input textbox.
            input:'ajs-input',
            // class name attached to ok button
            ok:'ajs-ok',
            // class name attached to cancel button 
            cancel:'ajs-cancel'
          }
        };
      }
  
  //
  $(document).on('click', '.hostpot', function() {    
      gralClic=false;
  });

  //Mostrar campo para la nueva galeria
  $("#btn_agregargaleria").click(function(){
    $("#cont_nueva_galeria").show();
  });
  $("#btn_aceptaragregargaleria").click(function(){
    if($("#nueva_galeria").val()!=""){
      $("#cont_nueva_galeria").hide();
      loadingAjax("cont_selgaleria");
      var params = {funct: 'agregarGaleria', galeria: $("#nueva_galeria").val()};        
      ajaxData(params, function(data){
          // console.log(data);
          if(data.success==true){
            idG = 0;
            var res = koolajax.callback(dropDownGaleria(idG));          
            // console.log(res);
            setTimeout(function(){
              $("#cont_selgaleria").html(res);          
            }, 1500);
          }
        });
    }      
  });
  

  // Limpiar filtros en la vista de prospectos
  $("#limpiarFiltrosProsp").click(function(){
    location.href='prospectos.php';
  });

  //Exportar ha excel los prospectos
  $("#exportarProsp").click(function(){
    var agenciaId = (parseInt($("#agenciaId").val()) > 0)?parseInt($("#agenciaId").val()):"";
    var vendedorId = $("#vendedorB").val();
    var prospecto = $("#nombreProspecB").val();
    var fechaDel = $("#filter_fechaDel").val();
    var fechaAl = $("#filter_fechaAl").val();

    var url ='exportacionesexcel.php';
    var arrParams = [];
    arrParams[0] = {"name":"btn_exp_prospectos", "val":""};
    arrParams[1] = {"name":"agenciaId", "val":agenciaId};
    arrParams[2] = {"name":"vendedorId", "val":vendedorId};
    arrParams[3] = {"name":"prospecto", "val":prospecto};
    arrParams[4] = {"name":"fechaDel", "val":fechaDel};
    arrParams[5] = {"name":"fechaAl", "val":fechaAl};
    postDinamico(url, arrParams);
  });    
  

  //Recargar el grid de zonas
  /*$(".btnMenu6").click(function(){
    versionZonaGrid.refresh();
    versionZonaGrid.commit();
  });*/

  if($("#filter_fechaDel").length){
    //Fecha del
    $("#filter_fechaDel").datepicker({
          showOn: "button",
          buttonImage: '../images/calendar.gif',
          buttonImageOnly: true,
          buttonText: "Select date",
          changeMonth: true,
          changeYear: true,
          // maxDate: "0Y",
          minDate: "-10Y",
          yearRange: "-10:+1",   
          defaultDate: $("#filter_fechaDel").val(),      
          onSelect: function(dateText, inst){ 
            $("#filter_fechaDel").val($(this).val());        
          }  
    });
  }
  if($("#filter_fechaAl").length){
    //Fecha al
    $("#filter_fechaAl").datepicker({
          showOn: "button",
          buttonImage: '../images/calendar.gif',
          buttonImageOnly: true,
          buttonText: "Select date",
          changeMonth: true,
          changeYear: true,
          //maxDate: "0Y",
          minDate: "-10Y",
          yearRange: "-10:+1",   
          defaultDate: $("#filter_fechaAl").val(),      
          onSelect: function(dateText, inst){ 
            $("#filter_fechaAl").val($(this).val());        
          }  
    });
  }


  // Limpiar filtros en la vista de estadistica
  $("#limpiarFiltrosEstadistica").click(function(){
    location.href='estadisticas.php';
  });

  /*//Exportar ha excel los prospectos
  $("#exportarProsp").click(function(){
    var agenciaId = (parseInt($("#agenciaId").val()) > 0)?parseInt($("#agenciaId").val()):"";
    var vendedorId = $("#vendedorB").val();
    var prospecto = $("#nombreProspecB").val();
    var fechaDel = $("#filter_fechaDel").val();
    var fechaAl = $("#filter_fechaAl").val();

    var url ='exportacionesexcel.php';
    var arrParams = [];
    arrParams[0] = {"name":"btn_exp_prospectos", "val":""};
    arrParams[1] = {"name":"agenciaId", "val":agenciaId};
    arrParams[2] = {"name":"vendedorId", "val":vendedorId};
    arrParams[3] = {"name":"prospecto", "val":prospecto};
    arrParams[4] = {"name":"fechaDel", "val":fechaDel};
    arrParams[5] = {"name":"fechaAl", "val":fechaAl};
    postDinamico(url, arrParams);
  }); */
  

  //Validar formulario registro de agencia
  if($("#formRegAgencia").length){
    $("#formRegAgencia").validate({
      submitHandler: function(form) { 
          // setLoading($(form).attr('id')); 
          form.submit();
        }
    });
  }


  // Imp. 12/02/20
  // Logica para filtros estadistica
  // $("#sel_tipo").hide();
  // $("#sel_agente").hide();
  // $("#sel_modelo").hide();

  // filtro opciones 
  $("#fil_opcion").change(function(){
    var fil_opcion = accounting.unformat($(this).val());
    console.log(fil_opcion);

    //Oculta y limpia valores
    // $("#sel_tipo").hide(); $("#fil_tipo").val(0);
    $("#sel_agente").hide(); $("#fil_agente").val(0);
    $("#sel_modelo").hide(); $("#fil_modelo").val(0);

    switch (fil_opcion) {
      case 1:
      case 2:
      case 4:      
      case 5:      
      case 6:
        $("#sel_agente").show();
      break;
      case 3:
        $("#sel_agente").show();
        $("#sel_modelo").show();
      break;
    }
  });

  //obtener valores de los filtros para setearlos
  if($("#hfil_opcion").length){
    var hfil_opcion = accounting.unformat($("#hfil_opcion").val());
    // var hfil_agente = accounting.unformat($("#hfil_agente").val());
    // var hfil_modelo = accounting.unformat($("#hfil_modelo").val());
    // console.log(hfil_opcion);
    // console.log(hfil_agente);
    // console.log(hfil_modelo);

    if(hfil_opcion>0){
      switch (hfil_opcion) {
        case 1:
        case 2:
        case 4:
        case 5:      
        case 6:
          $("#sel_agente").show();
        break;
        case 3:
          $("#sel_agente").show();
          $("#sel_modelo").show();
        break;
      }
    }    
  } 

  //Exportar ha excel los prospectos
  $("#exportarEstadistica").click(function(){
    var filter_fechaDel = $("#filter_fechaDel").val();
    var filter_fechaAl = $("#filter_fechaAl").val();
    var fil_opcion = accounting.unformat($("#fil_opcion").val());
    var fil_agente = accounting.unformat($("#fil_agente").val());
    var fil_modelo = accounting.unformat($("#fil_modelo").val());

    console.log(filter_fechaDel);
    console.log(filter_fechaAl);
    console.log(fil_opcion);
    console.log(fil_agente);
    console.log(fil_modelo);

    var url ='exportacionesexcel.php';
    var arrParams = [];
    arrParams[0] = {"name":"btn_exp_estadisticas", "val":""};
    arrParams[1] = {"name":"opcion", "val":fil_opcion};
    arrParams[2] = {"name":"agente", "val":fil_agente};
    arrParams[3] = {"name":"modelo", "val":fil_modelo};    
    arrParams[4] = {"name":"fechaDel", "val":filter_fechaDel};
    arrParams[5] = {"name":"fechaAl", "val":filter_fechaAl};
    postDinamico(url, arrParams);
  });


  // Imp. 26/02/20
  //Subir inventario por un archivo excel formulario registro de agencia
  if($("#formRegInventario").length){
    $("#formRegInventario").validate({
      submitHandler: function(form) { 
          // setLoading($(form).attr('id')); 
          $("#cont_msg_resp").hide();
          var htmlOriginal = showLoading('btnSubirInventario');          
          form.submit();
      }
    });
  }

});


//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//>>>METODOS ESPECIFICOS FUERA DEl $(document).ready<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

//desactivar un registro del grid seleccionado
function grid_activardesactivar(id, grid, valor){

  switch (grid) {
      case 'gamamodelo': 
        // console.log(id);
        // console.log(grid);
        // console.log(valor);
        params = {funct: 'ActivaDesactivaGModelo', id: id, valor:valor};
        ajaxData(params, function(data){
          // console.log("hola");
            if(data.success)
            {
                console.log("success");
                gamaModelosGrid.refresh();
                gamaModelosGrid.commit();
                alertify.success("Actualizado correctamente");
            }
            else
            {
                console.log("Else");
                alertify.error("Error, intente mas tarde");
            }
        });
      break;

      case 'versiongral':
        params = {funct: 'ActivaDesactivaVersionGral', id: id, valor:valor};
        ajaxData(params, function(data){
          // console.log("hola");
            if(data.success)
            {
                console.log("success");
                versionGeneralGrid.refresh();
                versionGeneralGrid.commit();
                alertify.success("Actualizado correctamente");
            }
            else
            {
                console.log("Else");
                alertify.error("Error, intente mas tarde");
            }
        });
      break;      
      case 'versionprecio':
        params = {funct: 'ActivaDesactivaVersionPrecio', id: id, valor:valor};
        ajaxData(params, function(data){
          // console.log("hola");
            if(data.success)
            {
                console.log("success");
                versionPrecioGrid.refresh();
                versionPrecioGrid.commit();
                alertify.success("Actualizado correctamente");
            }
            else
            {
                console.log("Else");
                alertify.error("Error, intente mas tarde");
            }
        });
      break;
      case 'versionplan':
        if(valor==0){ //Accion al desactivar una promocion
          alertify.confirm("<strong>&#191Est&aacute; seguro que desea desactivar el plan, si procede tambi&eacute;n se desactivar&aacute;n los  asociados en planes-modelo?</strong>", function(){
            var params = {funct: 'ActivaDesactivaVersionPlan', id: id, valor:valor};
            ajaxData(params, function(data){
                // console.log(data);
                if(data.success){                                        
                    versionPlanGrid.refresh();
                    versionPlanGrid.commit();
                    alertify.success("Actualizado correctamente");
                }else{                    
                    alertify.error("Error, intente mas tarde");
                }
            });
          },function(){
          }).set({labels:{ok:'Aceptar', cancel: 'Cancelar'}, padding: false});
        }else{
          var params = {funct: 'ActivaDesactivaVersionPlan', id: id, valor:valor};
          ajaxData(params, function(data){
              if(data.success){                  
                  versionPlanGrid.refresh();
                  versionPlanGrid.commit();
                  alertify.success("Actualizado correctamente");
              }else{                  
                  alertify.error("Error, intente mas tarde");
              }
          });
        }
      break;
      case 'versionrequisito':
        params = {funct: 'ActivaDesactivaVersionReq', id: id, valor:valor};
        ajaxData(params, function(data){
            if(data.success)
            {
                console.log("success");
                versionRequisitoGrid.refresh();
                versionRequisitoGrid.commit();
                alertify.success("Actualizado correctamente");
            }
            else
            {
                console.log("Else");
                alertify.error("Error, intente mas tarde");
            }
        });
      break;
      case 'versioncolor':
        params = {funct: 'ActivaDesactivaVersionColor', id: id, valor:valor};
        ajaxData(params, function(data){
            if(data.success)
            {
                console.log("success");
                versionColorGrid.refresh();
                versionColorGrid.commit();
                alertify.success("Actualizado correctamente");
            }
            else
            {
                console.log("Else");
                alertify.error("Error, intente mas tarde");
            }
        });
      break;
      case 'versionzona':
        params = {funct: 'ActivaDesactivaVersionZona', id: id, valor:valor};
        ajaxData(params, function(data){
            if(data.success)
            {
                console.log("success");
                versionZonaGrid.refresh();
                versionZonaGrid.commit();
                alertify.success("Actualizado correctamente");
            }
            else
            {
                console.log("Else");
                alertify.error("Error, intente mas tarde");
            }
        });
      break;

      case 'galeriadesdemodelo':        
        params = {funct: 'ActivaDesactivaGaleriaModelo', id: id, valor:valor};
        ajaxData(params, function(data){
          console.log(data);
            if(data.success){                
                galeriasModeloGrid.refresh();
                galeriasModeloGrid.commit();
                alertify.success("Actualizado correctamente");
            }else{
                alertify.error("Error, intente mas tarde");
            }
        });
      break;

      case 'versionpromocion': 
        // console.log(valor);
        if(valor==0){ //Accion al desactivar una promocion
          alertify.confirm("<strong>&#191Est&aacute; seguro que desea desactivar la promoci&oacute;n, si procede tambi&eacute;n se desactivar&aacute;n los  asociados en promociones-modelo?</strong>", function(){
              var params = {funct: 'ActivaDesactivaPromocion', id: id, valor:valor};
              console.log(params);
              ajaxData(params, function(data){
                console.log(data);
                  if(data.success){                
                      versionPromocionGrid.refresh();
                      versionPromocionGrid.commit();
                      alertify.success("Actualizado correctamente");
                  }else{
                      alertify.error("Error, intente mas tarde");
                  }
              });
          },function(){
          }).set({labels:{ok:'Aceptar', cancel: 'Cancelar'}, padding: false});
        }else{
          var params = {funct: 'ActivaDesactivaPromocion', id: id, valor:valor};
          console.log(params);
          ajaxData(params, function(data){
            console.log(data);
              if(data.success){                
                  versionPromocionGrid.refresh();
                  versionPromocionGrid.commit();
                  alertify.success("Actualizado correctamente");
              }else{
                  alertify.error("Error, intente mas tarde");
              }
          });
        }
              
      break;
  }
}
function mostrarAlertify()
{
  var opc = parseInt($("#tipoAviso").val());
  // console.log(typeof(opc));
        // console.log(opc);
          switch(opc) {
              case 0:
                  alertify.error("No se ha podido guardar el registro");
                  // console.log(opc);
                  break;
              case 1:
                  alertify.success("Cambios guardados correctamente");
                  // console.log(opc);
                  break;
              case 2:
                   // alertify.notify('custom message.', 'custom', 2, function(){console.log('dismissed');});
                  alertify.success("No hay cambios que guardar");
                  // console.log(opc);
                  break;
              case 3:
                alertify.error("Error al subir la imagen");
              break;
              default:
              console.log("default");
              break;
          }
}

//Mostrar alerta de mensajes personalizados
function msgAlertify($msj){  
  alertify.success($msj);
}

function verificaFormVG()
{
  // console.log($("#vgcaracteristicas").val());
  $.validator.setDefaults({
      success: "valid"
    });
   $( "#formVersionGral" ).validate({
      rules: {
        vgcaracteristicas: {
          required: true
        },
      }
    });
  tinymce.triggerSave();
  // console.log($("#vgcaracteristicas").val());
  $("#vgcarachidden").val($("#vgcaracteristicas").val());
    if($("#formVersionGral").valid())
    {
      if($("#vgcaracteristicas").val() == "")
      {
        alertify.error("Por favor llene las caracteristicas");
      }
      else
      {
        $("#formVersionGral").submit();
      }
    }
    else
    {
        alertify.error("Por favor rellene todos los campos para continuar");
    }
}

function mostrarFancyAgregarPlan(verPlanId, idTipoPlan)
{
  if(verPlanId == 0)
  {
    $("#idVersionPlan").val(0);
    $("#vp_idtipoplan").val("");
    var htmlOriginal = showLoading('btnGuardarPlan');
    setTimeout(function(){
      $("#div_vp_carac").html('<textarea class="form-control required" name="vp_carac" id="vp_carac" rows="4"></textarea>');
          // hideLoading('contenidoRec',html);
          var params = {selector:"#vp_carac", height:"230", btnImg:true};
          opcionesTinymce(params);

          // tinymce.init({
          //   selector: "#vp_carac",
          //   theme: "modern",
          //   plugins: [
          //   ["advlist autolink link image lists preview hr pagebreak"],
          //   ["searchreplace wordcount visualblocks visualchars code media"],
          //   ["save contextmenu directionality emoticons paste"]
          //   ],
          //   height : "230"
          // });
          hideLoading('btnGuardarPlan', htmlOriginal);
          // hideLoading('btnGuardarReco',htmlOriginal2);
        }, 1000);
  }
  else
  {
    params = {funct: 'obtVersionPlan', idVersionPlan: verPlanId};
    ajaxData(params, function(data){
      if(data.success)
      {
        $("#idVersionPlan").val(verPlanId);
        $("#vp_idtipoplan").val(idTipoPlan);
        var htmlOriginal = showLoading('btnGuardarPlan');
        setTimeout(function(){
          $("#div_vp_carac").html('<textarea class="form-control required" name="vp_carac" id="vp_carac" rows="4">'+data.caracteristicas+'</textarea>');
                  // hideLoading('contenidoRec',html);
                  var params = {selector:"#vp_carac", height:"230", btnImg:true};
                  opcionesTinymce(params);
                  // tinymce.init({
                  //   selector: "#vp_carac",
                  //   theme: "modern",
                  //   plugins: [
                  //   ["advlist autolink link image lists preview hr pagebreak"],
                  //   ["searchreplace wordcount visualblocks visualchars code media"],
                  //   ["save contextmenu directionality emoticons paste"]
                  //   ],
                  //   height : "230"
                  // });
                  hideLoading('btnGuardarPlan', htmlOriginal);
                  // hideLoading('btnGuardarReco',htmlOriginal2);
                }, 1000);
      }
    });
  }
}




function guardarVersionPlan()
{
  tinymce.triggerSave();
  if($("#formVersionPlan").valid())
  {
    if($("#vp_carac").val() == "")
    {
      alertify.error("Por favor llene las caracteristicas");
    }
    else
    {
      var htmlOriginal = showLoading('btnGuardarPlan');

      var caracteristicas = $.base64.encode($("#vp_carac").val());
      // console.log(caracteristicas);      
      var params = {
        funct: 'guardarVersionPlan', 
        idVersionGral: $("#idVersionGral").val(), 
        idVersionPlan: $("#idVersionPlan").val(),
        idTipoPlan: $("#vp_idtipoplan").val(),
        // caracteristicas: $("#vp_carac").val(),
        caracteristicas: caracteristicas,
        preconfigurado: (typeof $("#vp_preconfigurado").val() != 'undefined') ?1 :0,
      };      
      console.log(params);  

      ajaxData(params, function(data){
        if(data.success)
        {
          console.log("success");
          parent.$.fancybox.close();
          versionPlanGrid.refresh();
          versionPlanGrid.commit();
          hideLoading('btnGuardarPlan', htmlOriginal);
          if(data.res == 1)
          {                    
            alertify.success("Proceso finalizado correctamente");
          }
          if(data.res == 0)
          {
            alertify.error("Error al guardar registro");
          }
          if(data.res == 2)
          {
            alertify.success("Cambios guardados correctamente");
          }
          if(data.res == 3)
          {
            alertify.success("No hay cambios que guardar");
          }
        }
        else
        {
          console.log("Else");
          alertify.error("Error, intente mas tarde");
        }
      });
    }
  }
  else
  {
    alertify.error("invalido");
  }
}

function mostrarFancyAgregarRequisito(verReqId, concepto)
{
  $("#pdfCartaBuro").val("");
  $("#pdfSolCredito").val("");

  if(verReqId == 0)
  {
    $("#idVersionReq").val(0);
    $("#vr_concepto").val("");
    var htmlOriginal = showLoading('btnGuardarReq');
    setTimeout(function(){
      $("#div_vr_carac").html('<textarea class="form-control required" name="vr_carac" id="vr_carac" rows="4"></textarea>');
          // hideLoading('contenidoRec',html);
          var params = {selector:"#vr_carac", height:"230", btnImg:true};
          opcionesTinymce(params);
          // tinymce.init({
          //   selector: "#vr_carac",
          //   theme: "modern",
          //   plugins: [
          //   ["advlist autolink link image lists preview hr pagebreak"],
          //   ["searchreplace wordcount visualblocks visualchars code media"],
          //   ["save contextmenu directionality emoticons paste"]
          //   ],
          //   height : "230"
          // });
          hideLoading('btnGuardarReq', htmlOriginal);
          // hideLoading('btnGuardarReco',htmlOriginal2);
        }, 1000);
  }
  else
  {
    params = {funct: 'obtVersionReq', idVersionReq: verReqId};
    ajaxData(params, function(data){
      if(data.success)
      {
        $("#idVersionReq").val(verReqId);
        $("#vr_concepto").val(concepto);
        var htmlOriginal = showLoading('btnGuardarReq');
        //Limpiar contenedores 
        $("#addContPdfCartaburo").html('');
        $("#addContPdfSolcredito").html('');
        setTimeout(function(){          
          if(data.urlCartaBuro!=""){
            $("#addContPdfCartaburo").html('<a href="'+data.urlCartaBuro+'" target="_blank">Ver Archivo</a>');            
          }
          if(data.urlSolicitudCred!=""){
            $("#addContPdfSolcredito").html('<a href="'+data.urlSolicitudCred+'" target="_blank">Ver Archivo</a>');            
          }          

          $("#div_vr_carac").html('<textarea class="form-control required" name="vr_carac" id="vr_carac" rows="4">'+data.caracteristicas+'</textarea>');
                var params = {selector:"#vr_carac", height:"230", btnImg:true};
                opcionesTinymce(params);
                
                  // hideLoading('contenidoRec',html);
                  // tinymce.init({
                  //   selector: "#vr_carac",
                  //   theme: "modern",
                  //   plugins: [
                  //   ["advlist autolink link image lists preview hr pagebreak"],
                  //   ["searchreplace wordcount visualblocks visualchars code media"],
                  //   ["save contextmenu directionality emoticons paste"]
                  //   ],
                  //   height : "230"
                  // });
                  hideLoading('btnGuardarReq', htmlOriginal);
                  // hideLoading('btnGuardarReco',htmlOriginal2);
                }, 1000);
      }
    });
  }
}

function guardarVersionReq()
{
  tinymce.triggerSave();
  if($("#formVersionReq").valid())
  {
    if($("#vr_carac").val() == "")
    {
      alertify.error("Por favor llene las caracteristicas");
    }
    else
    {
      
      var htmlOriginal = showLoading('btnGuardarReq');
      var caracteristicas = $.base64.encode($("#vr_carac").val());
      // console.log(caracteristicas);
            var params = {
        funct: 'guardarVersionReq', 
        idVersionGral: $("#vr_idVersionGral").val(), 
        idVersionReq: $("#idVersionReq").val(),
        concepto: $("#vr_concepto").val(),
              // caracteristicas: $("#vr_carac").val(),
              caracteristicas: caracteristicas,
        preconfigurado: (typeof $("#vr_preconfigurado").val() != 'undefined') ?1 :0,
      };
      ajaxData(params, function(data){
        console.log(data);

        if(data.success)
        {
          var reqVersIdTmp = data.reqVersId; //Es el id del registro de los requisitos          

          parent.$.fancybox.close();
          versionRequisitoGrid.refresh();
          versionRequisitoGrid.commit();
          hideLoading('btnGuardarReq', htmlOriginal);
          if(data.res == 1)
          {                    
            alertify.success("Proceso finalizado correctamente");            
          }
          if(data.res == 0)
          {
            alertify.error("Error al guardar registro");
          }
          if(data.res == 2)
          {
            alertify.success("Cambios guardados correctamente");
          }
          // if(data.res == 3)
          // {
          //   alertify.success("No hay cambios que guardar");
          // }

          //Subir archivos si la respuesta es 1 o es 2
          if(data.res==1 || data.res==2 || data.res==3){
              //>>Subir imagenes en caso de existir      
              var file1 = $('input#pdfCartaBuro')[0].files[0];
              var file2 = $('input#pdfSolCredito')[0].files[0];                
              var urlUplImg = '../uploadfiles.php?funct=uploadGeneralImages';

              if(typeof file1 !== 'undefined'){              
                var data = new FormData();        
                data.append('file', file1);
                data.append('saveFolder', "upload/requisitos/");
                ajaxDataImg(urlUplImg, data, function(dataResp){
                  // console.log(dataResp);
                  //Actualizar en la tabla por su id de requisito
                  if(dataResp.resp==true){                    
                    var paramsImg = {funct:'ActVersionReqPorCampo', reqVersId:reqVersIdTmp, campo:'urlCartaBuro', valor:dataResp.ruta};
                    // console.log(paramsImg);
                    ajaxData(paramsImg, function(dataImgRes){  
                        // console.log(dataImgRes);
                        if(dataImgRes.success==true){
                          alertify.success("Archivo subido correctamente");
                        }else{
                          alertify.success("Archivo no subio, intentar más tarde");
                        }
                    });
                  }
                });      
              }
              if(typeof file2 !== 'undefined'){
                var data = new FormData();        
                data.append('file', file2);
                data.append('saveFolder', "upload/requisitos/");
                ajaxDataImg(urlUplImg, data, function(dataResp){
                  // console.log(dataResp);
                  //Actualizar en la tabla por su id de requisito
                  if(dataResp.resp==true){                    
                    var paramsImg = {funct:'ActVersionReqPorCampo', reqVersId:reqVersIdTmp, campo:'urlSolicitudCred', valor:dataResp.ruta};
                    // console.log(paramsImg);
                    ajaxData(paramsImg, function(dataImgRes){  
                        // console.log(dataImgRes);
                        if(dataImgRes.success==true){
                          alertify.success("Archivo subido correctamente");
                        }else{
                          alertify.success("Archivo no subio, intentar más tarde");
                        }
                    });
                  }
                });   
              }
          }
        }
        else
        {
          console.log("Else");
          alertify.error("Error, intente mas tarde");
        }
      });            
    }
  }
  else
  {
    alertify.error("invalido");
  }
}



function mostrarFancyAgregarColor(verColorId, color, imagenAuto, imagenColor)
{
  $('#formVersionColor')[0].reset();
  
  if(verColorId == 0)
  {
    $("#idVersionColor").val(0);
    $("#vc_color").val("");
    $("#vc_imagena").removeClass("required");
    $("#vc_imagenc").removeClass("required");
    $("#vc_imagena").addClass("required");
    $("#vc_imagenc").addClass("required");
    $("#divMostrarImgAuto").html('');
    $("#divMostrarImgColor").html('');
  }
  else
  { 
    $("#idVersionColor").val(verColorId);
    $("#vc_color").val(color);
    $("#vc_imagena").removeClass("required");
    $("#vc_imagenc").removeClass("required");
    $("#divMostrarImgAuto").html('<img src="'+imagenAuto+'" height="68"><br/>'+
      '<input type="hidden" name="hid_imagen" id="hid_imagen" value="'+imagenAuto+'">');
    $("#divMostrarImgColor").html('<img src="'+imagenColor+'" height="68"><br/>'+
      '<input type="hidden" name="hid_imagenc" id="hid_imagenc" value="'+imagenColor+'">');
  }
}

function guardarVersionColor()
{
  if( $("#formVersionColor").valid() )
  {
    $("#formVersionColor").submit();
  }
}

function eliminarColorVersion(coloresVersId)
{
  alertify.confirm("<strong>&#191Est&aacute; seguro que desea eliminar este color, si procede también eliminará las galerías asociadas?</strong>", function(){
    confirmElimColorVersion(coloresVersId);
  },function(){
  }).set({labels:{ok:'Aceptar', cancel: 'Cancelar'}, padding: false});
}

function confirmElimColorVersion(coloresVersId)
{
  params = {
    funct: 'eliminarColorVersion', 
    coloresVersId: coloresVersId, 
  };
  ajaxData(params, function(data){
    console.log(data);
    if(data.success)
    {
      versionColorGrid.refresh();
      versionColorGrid.commit();
      if(data.res == 1)
      {
        alertify.success("Proceso finalizado correctamente");
      }
      if(data.bloquearTab)
      {
        if(!$("#btnMenu2").hasClass("tabdisabled"))
        {
          $("#btnMenu2").addClass("tabdisabled");
        }
        if(!$("#btnMenu6").hasClass("tabdisabled"))
        {
          $("#btnMenu6").addClass("tabdisabled");
        }
      }
    }
    else
    {
      console.log("Else");
      alertify.error("Error, intente mas tarde");
    }
  });
}

function cambiarColorImgPrincipal(idColorImgPrincipal)
{     
  var url = new URL(window.location.href);
  var verId = url.searchParams.get("verId");    
  location.href='version.php?verId='+verId+"&idColorImgPrincipal="+idColorImgPrincipal;
    // location.href=window.location+"&idColorImgPrincipal="+idColorImgPrincipal;
}

function mostrarFancyEditarImgGaleria(zonaVersId, idImagen, titulo, descripcion, imagen, precio)
{
  $('#formImagenGal')[0].reset();
  
  descripcion = $("#descripcion_img_"+idImagen).val();
  titulo = $("#titulo_img_"+idImagen).val();
  $("#imagen_imggal").attr("src",imagen);
  $("#idimagen_imggal").val(idImagen);
  $("#zonaVersId_imggal").val(zonaVersId);
  $("#titulo_imggal").val(titulo);

  console.log(precio)
  // var precio = (precio=="")?"":accounting.formatColumn([precio], "");
  $("#precio_imggal").val(precio);

  var htmlOriginal = showLoading('btnGuardarImgGal');
  setTimeout(function(){
    $("#div_desc_imggal").html('<textarea class="form-control" rows="4" name="descripcion_imggal" id="descripcion_imggal">'+descripcion+'</textarea>');
      // hideLoading('contenidoRec',html);
      var params = {selector:"#descripcion_imggal", height:"230", btnImg:false};
      opcionesTinymce(params);
      // tinymce.init({
      //   selector: "#descripcion_imggal",
      //   theme: "modern",            
      //   plugins: [
      //   ["advlist autolink link image lists preview hr pagebreak"],
      //   ["searchreplace wordcount visualblocks visualchars code media"],
      //   ["save contextmenu directionality emoticons paste"]
      //   ],
      //   height : "170"
      // });
      hideLoading('btnGuardarImgGal', htmlOriginal);
      // hideLoading('btnGuardarReco',htmlOriginal2);
    }, 1000);
    // $("#descripcion_imggal").val(descripcion);
}

function guardarImgGal()
{
  tinymce.triggerSave();
  var file = $('input#imgGalSubir')[0].files[0];
  if($("#formImagenGal").valid())
  {
    var htmlOriginal = showLoading('btnGuardarImgGal');
    var precio = ($("#precio_imggal").val()!="0.00" && $("#precio_imggal").val()!="0") ?$("#precio_imggal").val() :"";  

    if(typeof file !== 'undefined')
    { 
      console.log("con archivo");
      console.log(file);
        var data = new FormData();
        data.append('file', file);
        data.append('zonaVersId',$("#zonaVersId_imggal").val());
        data.append('idImagen', $("#idimagen_imggal").val());
        data.append('titulo', $("#titulo_imggal").val());
        data.append('descripcion',$("#descripcion_imggal").val());
        data.append('precio', precio);

        $.ajax({
          type: "POST",
          url: '../uploadfiles.php?funct=uploadImageIndGM',
          data: data,
          cache: false,
          contentType: false,
          processData: false,
          success: function (rponse) {
              console.log(rponse);
              hideLoading('btnGuardarImgGal', htmlOriginal);
              parent.$.fancybox.close();
              if(rponse != '')
              {
                versionZonaActivaGrid.refresh();
                versionZonaActivaGrid.commit();
                if(rponse > 0)
                {
                    alertify.success("Proceso finalizado correctamente");
                }
              }
              else
              {
                alertify.error("Error al subir imagen");
              }
          }
       });
    }
    else
    {
      console.log("sin archivo");
    params = {
      funct: 'guardarImagenGaleria',
      zonaVersId: $("#zonaVersId_imggal").val(),
      idImagen: $("#idimagen_imggal").val(),
      titulo: $("#titulo_imggal").val(),
      descripcion: $("#descripcion_imggal").val(),
      precio: precio,      
    };
    ajaxData(params, function(data){
          hideLoading('btnGuardarImgGal', htmlOriginal);
      if(data.success)
      {
        parent.$.fancybox.close();
        versionZonaActivaGrid.refresh();
        versionZonaActivaGrid.commit();
        if(data.res > 0)
        {
          alertify.success("Proceso finalizado correctamente");
        }                  
      }
      else
      {
        console.log("Else");
        alertify.error("Error, intente mas tarde");
      }
    });
    }
  }
  else
  {
    console.log("Invalido");
  }
}

function eliminarZonaVersion(zonaVersId)
{
  alertify.confirm("<strong>&#191Esta seguro que desea eliminar la zona seleccionada?</strong><br>Tambi&eacute;n se eliminar&aacute; la galeria de imagenes si la tiene", function(){
    confirmEliminarZona(zonaVersId);
  },function(){
    $("#idDocumentoEliminar").val("");
    $("#nombreDocumentoEliminar").val("");
    // alertify.error('Cancelar');
  }).set({labels:{ok:'Aceptar', cancel: 'Cancelar'}, padding: false});
}

function confirmEliminarZona(zonaVersId)
{
  params = {
    funct: 'EliminarZona',
    zonaVersId: zonaVersId,
  };
  ajaxData(params, function(data){
    if(data.success)
    {
      parent.$.fancybox.close();
      versionZonaGrid.refresh();
      versionZonaGrid.commit();
      if(data.res > 0)
      {
        $(".hostpot").each(function(){
          if($(this).attr("data-activazonaid") == data.activaZonaId)
          {
            $(this).remove();
          }
        });
        alertify.success("Proceso finalizado correctamente");
      }
    }
    else
    {
      console.log("Else");
      alertify.error("Error, intente mas tarde");
    }
  });
}

function agregarImgGal()
{
  if($("#formAgregarImgGal").valid())
  {
    $("#formAgregarImgGal").submit();
  }
}

function eliminarImgGaleria(activazonaid,idImagen)
{
  alertify.confirm("<strong>&#191Esta seguro que desea eliminar la imagen seleccionada?</strong>", function(){
    confirmEliminarImgGal(activazonaid, idImagen);
  },function(){
  }).set({labels:{ok:'Aceptar', cancel: 'Cancelar'}, padding: false});
}

function confirmEliminarImgGal(activazonaid, idImagen)
{
 params = {
    funct: 'EliminarImgGal',
    activazonaid: activazonaid,
    idImagen: idImagen,
  };
  ajaxData(params, function(data){
    if(data.success)
    {
      if(data.res > 0)
      {
        alertify.success("Proceso realizado correctamente.");
        versionZonaActivaGrid.refresh();
        versionZonaActivaGrid.commit();
      }
      else
      {
        alertify.error("No se pudo completar");
      }
    }
    else
    {
      alertify.error("Error inesperado, intenta mas tarde");
    }
  });
}


//Pasa las galerias dentro de la vista de gama de modelos
function mostrarFancyEditarImgGaleriaGamaModelo(galeriaModeloId, gModeloId, galeriaId, titulo, imagen, precio)
{
  $('#formImagenGal')[0].reset();
  var descripcion = $("#descripcion_img_"+galeriaModeloId).val();
  $("#titulo_imggal").val(titulo);
  $("#imagen_imggal").attr("src",imagen);
  $("#precio_imggal").val(precio);
  $("#idActImg").val(galeriaModeloId);
  $("#act_id_galeria").val(galeriaId);
  
  // titulo = $("#titulo_img_"+galeriaModeloId).val();  
  // $("#idimagen_imggal").val(idImagen);
  // $("#zonaVersId_imggal").val(zonaVersId);  

  var htmlOriginal = showLoading('btnGuardarImgGal');
  setTimeout(function(){
    $("#div_desc_imggal").html('<textarea class="form-control" rows="4" name="descripcion_imggal" id="descripcion_imggal">'+descripcion+'</textarea>');
      // hideLoading('contenidoRec',html);
      var params = {selector:"#descripcion_imggal", height:"230", btnImg:false};
      opcionesTinymce(params);      
      hideLoading('btnGuardarImgGal', htmlOriginal);
    }, 1000);    
}

function guardarImgGalGamaModelo()
{
  tinymce.triggerSave();
  var file = $('input#imgGalSubir')[0].files[0];
  if($("#formImagenGal").valid())
  {
        var htmlOriginal = showLoading('btnGuardarImgGal');
        var precio = ($("#precio_imggal").val()!="0.00" && $("#precio_imggal").val()!="0") ?$("#precio_imggal").val() :"";  
                
        var data = new FormData();
        if(typeof file !== 'undefined'){
          data.append('file', file);
          data.append('archivoSubir',1);  
        }else{
          data.append('archivoSubir',0);
        }
        data.append('galeriaModeloId',$("#idActImg").val());
        data.append('gModeloId',$("#idGM").val());
        data.append('galeriaId',$("#act_id_galeria").val());      
        data.append('titulo', $("#titulo_imggal").val());        
        data.append('descripcion',$("#descripcion_imggal").val());
        data.append('imagen', $("#imagen_imggal").attr("src"));
        data.append('precio', precio);

        $.ajax({
          type: "POST",
          url: '../uploadfiles.php?funct=actualizarImagesFromGM',
          data: data,
          cache: false,
          contentType: false,
          processData: false,
          success: function (rponse) {
              console.log(rponse);
              hideLoading('btnGuardarImgGal', htmlOriginal);
              parent.$.fancybox.close();
              if(rponse != ''){                
                if(rponse > 0){
                    alertify.success("Proceso finalizado correctamente");
                    setTimeout(function(){           
                       location.reload();
                    }, 500);
                }
              }else{
                alertify.error("Error al actualizar");
              }
          }
       });      
  }
  else
  {
    console.log("Invalido");
  }
}
function eliminarImgGaleriaModelo(galeriaModeloId)
{
  alertify.confirm("<strong>&#191Esta seguro que desea eliminar la imagen seleccionada?</strong>", function(){
    var data = new FormData();
    data.append('galeriaModeloId',galeriaModeloId);

    $.ajax({
      type: "POST",
      url: '../uploadfiles.php?funct=eliminarImagesFromGM',
      data: data,
      cache: false,
      contentType: false,
      processData: false,
      success: function (rponse) {
          console.log(rponse);          
          if(rponse != ''){          
                 alertify.success("Proceso finalizado correctamente");
                 setTimeout(function(){           
                    location.reload();
                 }, 500);
          }else{
             alertify.error("Error al actualizar");
          }
      }
   });    
  },function(){
  }).set({labels:{ok:'Aceptar', cancel: 'Cancelar'}, padding: false});
}

// Eliminar video modelo
function eliminarVideoModelo(videoModeloId)
{
  alertify.confirm("<strong>&#191Esta seguro que desea eliminar el video?</strong>", function(){
    var data = new FormData();
    data.append('videoModeloId',videoModeloId);

    $.ajax({
      type: "POST",
      url: '../uploadfiles.php?funct=eliminarVideoFromGM',
      data: data,
      cache: false,
      contentType: false,
      processData: false,
      success: function (rponse) {
          console.log(rponse);          
          if(rponse != ''){          
                 alertify.success("Proceso finalizado correctamente");
                 setTimeout(function(){           
                    location.reload();
                 }, 500);
          }else{
             alertify.error("Error al actualizar");
          }
      }
   });
  },function(){
  }).set({labels:{ok:'Aceptar', cancel: 'Cancelar'}, padding: false});
}


//Mostrar fancy promocion
function mostrarFancyAgregarPromocion(promocionId, concepto)
{
  if(promocionId == 0)
  {
    $("#idPromocion").val(0);
    $("#promo_concepto").val("");
    var htmlOriginal = showLoading('btnGuardarPromo');
    setTimeout(function(){
      $("#div_promo_carac").html('<textarea class="form-control required" name="promo_carac" id="promo_carac" rows="4"></textarea>');
          // hideLoading('contenidoRec',html);
          var params = {selector:"#promo_carac", height:"230", btnImg:true};
          opcionesTinymce(params);          
          hideLoading('btnGuardarPromo', htmlOriginal);
          // hideLoading('btnGuardarReco',htmlOriginal2);
        }, 1000);
  }
  else
  {   
    params = {funct: 'obtPromocionPorId', idPromocion: promocionId};
    ajaxData(params, function(data){
      if(data.success)
      {
        $("#idPromocion").val(promocionId);
        $("#promo_concepto").val(concepto);
        var htmlOriginal = showLoading('btnGuardarPromo');
        setTimeout(function(){
          $("#div_promo_carac").html('<textarea class="form-control required" name="promo_carac" id="promo_carac" rows="4">'+data.caracteristicas+'</textarea>');
                var params = {selector:"#promo_carac", height:"230", btnImg:true};
                opcionesTinymce(params);
                hideLoading('btnGuardarPromo', htmlOriginal);                
        }, 1000);
      }
    });
  }
}
function guardarPromocion()
{
  tinymce.triggerSave();
  if($("#formVersionPromo").valid())
  {
    if($("#promo_carac").val() == "" || $("#promo_concepto").val() == "")
    {
      alertify.error("Por favor llene el concepto y las características");
    }
    else
    {
      var htmlOriginal = showLoading('btnGuardarPromo');
      var caracteristicas = $.base64.encode($("#promo_carac").val());
      // console.log(caracteristicas);
          var params = {
        funct: 'guardarCatPromocion', 
        // idVersionGral: $("#vr_idVersionGral").val(), 
        idPromocion: $("#idPromocion").val(),
        concepto: $("#promo_concepto").val(),
            // caracteristicas: $("#promo_carac").val(),
            caracteristicas: caracteristicas,
        preconfigurado: (typeof $("#promo_preconfigurado").val() != 'undefined') ?1 :0,
      };
      
      ajaxData(params, function(data){
        console.log(data);
        if(data.success == true)
        {
          console.log("success");
          parent.$.fancybox.close();
          versionPromocionGrid.refresh();
          versionPromocionGrid.commit();
          hideLoading('btnGuardarPromo', htmlOriginal);
          if(data.res == 1){
            alertify.success("Proceso finalizado correctamente");
          }else{
            alertify.error("Error al guardar registro");
          }
        }
        else
        {
          console.log("Else");
          alertify.error("Error, intente mas tarde");
        }
      });
    }
  }
  else
  {
    alertify.error("invalido");
  }
}



function Handle_OnRowConfirmEdit(sender,args)
{
  var _row = args["Row"];
  console.log(sender.id);
  var tabla = "";
  if(sender.id == "versionPrecioGrid")
  {
    tabla = "version_precios";
  }
  if(sender.id == "usuariosGrid")
  {
    tabla = "usuarios";
  }
  if(sender.id == "agenciaGrid") //Actualiza la tabla de usuarios
  {
    tabla = "usuarios";
  }
  if(tabla != "")
  {
    // console.log("hola2");
    params = {funct: 'updActualizacion', tabla:tabla};
    ajaxData(params, function(data){
      if(data.success){
        console.log("actualizado");
      }
    });
  }
}

function Handle_OnConfirmInsert(sender,args)
{
  console.log(sender.id);
  var tabla = "";
  if(sender.id == "versionPrecioGrid")
  {
    tabla = "version_precios";
  }
  if(sender.id == "usuariosGrid")
  {
    tabla = "usuarios";
  }
  if(tabla != "")
  {
    params = {funct: 'updActualizacion', tabla:tabla};
    ajaxData(params, function(data){
      if(data.success){
        console.log("actualizado");
      }
    });
  }
}
//Para eliminar y hacer actualizacion 
function Handle_OnRowDelete(sender,args)
{
  console.log(sender.id);
  var tabla = "";
  if(sender.id == "versionPrecioGrid")
  {
    tabla = "version_precios";
  }
  if(sender.id == "usuariosGrid")
  {
    tabla = "usuarios";
  }
  if(tabla != "")
  {
    params = {funct: 'updActualizacion', tabla:tabla};
    ajaxData(params, function(data){
      if(data.success){
        console.log("actualizado");
      }
    });
  }
}


//Acciones necesarias para cuando se muestra el fancy desactivar usuarios en catalogos
function muestraDesactivarUsuario(idUsuario, nombre, activo)
{
  console.log(idUsuario);
  $("#idUsuarioDesactivar").val(idUsuario);
  var opcion = "Desactivar";
  var opc = 0;
  if(activo == 0)
  {
    opcion = "Activar";
    opc = 1;
  }
  $("#activoUsuario").val(opc);
  $("#btnDesactivarUsuario").val(opcion);
  $("#opcionUsuario").html(opcion);
  $("#nombreUsuarioText").html(nombre);
}

//Metodo para seleccionar por defecto el auto que se mostrara primeo en la mesa de control en la app
function selPorDefecto(idColor) {
  // console.log(idColor);
  var urlPage = new URL(window.location.href);
  var verId = urlPage.searchParams.get("verId");    

  var params = {funct: 'actColorPordefecto', idColor: idColor, gralVersId:verId};
  ajaxData(params, function(data){      
    versionColorGrid.refresh();
    versionColorGrid.commit();

    if(data.success==true){                      
        var url ='version.php?verId='+verId;//+"#menu2";            
        var arrParams = [];
        arrParams[0] = {"name":"idVersionColorPordefecto", "val":verId};
        postDinamico(url, arrParams);
      }else{
        alertify.success("Error, intente mas tarde");
        return false;
      }
    });
}

//Metodo que elimina el registro desde un grid
function eliminar_reg(id, opc){
  console.log(id);
  console.log(opc);
  var msg = '&#191Est&aacute; seguro de eliminar esta fila';
  switch (opc){
    case 'gama_modelo':
      msg += ", si es asi se borraran las versiones que lo contengan";
      alertify.confirm("<strong>"+msg+ "?</strong>", function(){        
        var url ='gamamodelos.php';
        var arrParams = [];
        arrParams[0] = {"name":"gModeloId", "val":id};
        arrParams[1] = {"name":"eliminarModelo", "val":"ok"};
        postDinamico(url, arrParams);
      },function(){        
      }).set({labels:{ok:'Aceptar', cancel: 'Cancelar'}, padding: false});
    break;
    case 'version_modelo':
      msg += ", si es asi se borraran todo su contenido interior";
      alertify.confirm("<strong>"+msg+ "?</strong>", function(){        
        var url ='versiones.php';
        var arrParams = [];
        arrParams[0] = {"name":"gralVersId", "val":id};
        arrParams[1] = {"name":"eliminarVersion", "val":"ok"};
        postDinamico(url, arrParams);
      },function(){        
      }).set({labels:{ok:'Aceptar', cancel: 'Cancelar'}, padding: false});
    break;
    case 'promociones':
      msg += ", si es asi se borraran las promociones asociadas en promociones-modelo";
      alertify.confirm("<strong>"+msg+ "?</strong>", function(){        
        var url ='catalogos.php?catalog=catPromociones';
        var arrParams = [];
        arrParams[0] = {"name":"promocionId", "val":id};
        arrParams[1] = {"name":"eliminarPromocion", "val":"ok"};
        postDinamico(url, arrParams);
      },function(){        
      }).set({labels:{ok:'Aceptar', cancel: 'Cancelar'}, padding: false});
    break 
    case 'planespreconf':
      msg += ", si es asi se borraran los planes asociados en planes-modelo";
      alertify.confirm("<strong>"+msg+ "?</strong>", function(){        
        var url ='catalogos.php?catalog=catPlanesPreconfigurados';
        var arrParams = [];
        arrParams[0] = {"name":"planId", "val":id};
        arrParams[1] = {"name":"eliminarPlanPreconf", "val":"ok"};
        postDinamico(url, arrParams);
      },function(){        
      }).set({labels:{ok:'Aceptar', cancel: 'Cancelar'}, padding: false});    
    break 
    case 'prospecto':
      alertify.confirm("<strong>"+msg+ "?</strong>", function(){
        var url ='prospectos.php';
        var arrParams = [];
        arrParams[0] = {"name":"prospectoId", "val":id};
        arrParams[1] = {"name":"eliminarProspecto", "val":"ok"};
        postDinamico(url, arrParams);
      },function(){
      }).set({labels:{ok:'Aceptar', cancel: 'Cancelar'}, padding: false});    
    break 
  }
}

//Opciones del editor tinymce
function opcionesTinymce(params){
  // console.log(params.btnImg);
  if(params.btnImg==true){
      tinymce.init({
      readonly : params.readonly,  //1=solo lectura, 0=editable
      selector: params.selector,
      height : params.height,
      // theme: "modern",
      menu: {
        file: {title: 'File'},
        edit: {title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall'},
        insert: {title: 'Insert', items: 'link | media | image '},
        view: {title: 'View', items: 'preview'},
        format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
        table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'},
        tools: {title: 'Tools', items: 'spellchecker code'}
      },
      plugins: [
        ["advlist autolink link image lists preview"],
        ["code media"],
        ["save contextmenu directionality emoticons paste"]
      ],  
      media_live_embeds: true,  
      file_picker_types: 'image media',
      image_title: true,                 
      automatic_uploads: true,              
      file_browser_callback_types: 'image media',
      file_browser_callback: function(field_name, url, type, win) {
        win.document.getElementById(field_name).value = 'my browser value';
      },
      file_picker_callback: function(callback, value, meta) {
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        // input.setAttribute('accept', 'media/*');

        input.onchange = function() {
          var file = this.files[0];
          
          var reader = new FileReader();
          reader.onload = function () {                      
            // console.log(file);

            var data = new FormData();
            data.append('file', file);
            $.ajax({
              type: "POST",
              url: '../uploadfiles.php?funct=uploadImagesTinymce',
              data: data,
              cache: false,
              contentType: false,
              processData: false,
              success: function (rponse) {
                // console.log(rponse);
                objJson = JSON.parse(rponse);
                if(objJson.resp==true){
                  console.log(objJson);
                  callback(objJson.ruta, {alt: ""});
                }                            
              }
            });
          };
          reader.readAsDataURL(file);
        };      
        input.click();                
      },

    });   
  }else{    
    tinymce.init({
      selector: params.selector,
      height : params.height,
      // theme: "modern",
      menu: {
        file: {title: 'File'},
        edit: {title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall'},
        insert: {title: 'Insert', items: 'link media '},
        view: {title: 'View', items: 'preview'},
        format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
        table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'},
        tools: {title: 'Tools', items: 'spellchecker code'}
      },
      plugins: [
        ["advlist autolink link lists preview"],
        ["code "],
        ["save contextmenu directionality emoticons paste"]
      ],
    }); 
  }  
}

//Ver el detalle completo en un popup
function verDetallePopup(id, tipo){
  if(tipo=="plan"){
    var html = $("#caracplan_"+id).html();
  }
  if(tipo=="promo"){
    var html = $("#caracpromo_"+id).html();
  }   
  if(tipo=="requisito"){
    var html = $("#caracreq_"+id).html();
  }  
  $("#contenidodin_popup").html("");
  $("#contenidodin_popup").html(html);  
}

//Crear planes desde planes preconfigurados
function guardarVersionPlanPre(){
  var idcollPlan = [];        
  $('.contListaPlanPre input:checkbox[class="selectCheck"]').each(function(){                         
      console.log("Entre");
       if($(this).is(':checked')) {  
          idcollPlan.push(this.value);
      }                     
  });

  if(idcollPlan.length>0){
    // console.log(idcollPlan.join());
    var htmlOriginal = showLoading('btnGuardarPlanPre');
    
    var params = {funct: 'crearPlanesDesdePre', ids:idcollPlan.join(), gralVersId:$("#planes_pre_verId").val()};
    ajaxData(params, function(data){
      console.log(data);
      parent.$.fancybox.close();
      hideLoading('btnGuardarPlanPre', htmlOriginal);

      if(data.success==true){
          versionPlanGrid.refresh();
          versionPlanGrid.commit();          
          alertify.success("Se salvo correctamentelos planes");
      }else{
        alertify.error("No fue posible salvar los planes");
      }      
    });
  }else{
    alertify.error("Seleccionar al menos un plan");
  }
}

//Crear requisitos desde requisitos preconfigurados
function guardarVersionReqPre(){
  var idcollRequisito = [];        
  $('.contListaReqPre input:checkbox[class="selectCheck"]').each(function(){
      console.log("Entre");
       if($(this).is(':checked')) {
          idcollRequisito.push(this.value);
      }
  });

  if(idcollRequisito.length>0){
    // console.log(idcollRequisito.join());
    var htmlOriginal = showLoading('btnGuardarReqPre');

    var params = {funct: 'crearReqDesdePre', ids:idcollRequisito.join(), gralVersId:$("#req_pre_verId").val()};
    ajaxData(params, function(data){
      console.log(data);
      parent.$.fancybox.close();
      hideLoading('btnGuardarReqPre', htmlOriginal);

      if(data.success==true){
          versionRequisitoGrid.refresh();
          versionRequisitoGrid.commit();
          alertify.success("Se salvo correctamente los requisitos");
      }else{
        alertify.error("No fue posible salvar los requisitos");
      }
    });
  }else{
    alertify.error("Seleccionar al menos un requisito");
  }
}



// setTimeout(function(){ 
//   $("#mod_1_vers_1").prop('checked', true);
//   $("#mod_1_vers_1").addClass('active');
// }, 0);

//>>>>Para los planes - Modelos
$(function(){
  $("#id_plan").change(function(){
    // console.log($(this).val());
    $('.checktree li ul input').removeClass("active");
    $('.checktree li ul input').prop('checked', false); //hijos
    $('.checktree li input').prop('checked', false); //Padres
    //Limpiar contendedores
    $("#cont_caracteristicas").html("");
    $("#cont_verDetallePopup").html("");

    if($(this).val()!=""){
        $(".cont_modelos_versiones").show();

        //Obtener ids de las versiones por el id del plan seleccionado          
        var params = {funct: 'obtVersionPlanPorIdPadre', idPadre:$(this).val()};
        ajaxData(params, function(data){
          // console.log(data);                
          if(data.success==true){
            $.each(data.colVersiones, function(i,v){
                var target = "#vers_"+v.gralVersId;
                $(target).prop('checked', true);
                $(target).addClass('active');
            });        
          }else{
            $('.checktree li ul input').removeClass("active");
            $('.checktree li ul input').prop('checked', false); //hijos
            $('.checktree li input').prop('checked', false); //Padres          
          }
        });

        //Obtener datos del plan por su id      
        var idVersionPlan = $(this).val();      
        var paramsPlan = {funct: 'obtVersionPlan', idVersionPlan:idVersionPlan};
        ajaxData(paramsPlan, function(data){            
            var html = '<div id="caracplan_'+idVersionPlan+'" style="display:none;">'+data.caracteristicas+'</div>';
            $("#cont_caracteristicas").html(html);
            var link = '<a href="javascript:void(0);" class="badge badge-light my_popup_open" onclick="verDetallePopup('+idVersionPlan+', \'plan\');">Ver detalle</a>';
            $("#cont_verDetallePopup").html(link);
        });

    }else{
      $(".cont_modelos_versiones").hide();
    }    
  });
});
//obtener los ids de las versiones para crear los planes seleccionados
function salvarPlanesVersionesModelos() {
  var idPlan = $("#id_plan").val();
  if(idPlan != ""){
    var element = $('.checktree li ul input.active').length;    
    var arrIdsGralVersion = [];
    if(element>0){
      $('.checktree li ul input.active').each(function(index, value) {        
        arrIdsGralVersion.push($(this).val());
      });
    }
    // else{
    //   alertify.error("Seleccionar al menos una versión de algún modelo");
    //   return false;
    // }

    console.log(arrIdsGralVersion.join());
    var htmlOriginal = showLoading('btnGuardarPlanPre');

    var url ='planestreeview.php';
    var arrParams = [];
    arrParams[0] = {"name":"arrIdsPlan", "val":idPlan};
    arrParams[1] = {"name":"arrIdsGralVersion", "val":arrIdsGralVersion};    
    postDinamico(url, arrParams);

  }else{
    alertify.error("Seleccionar al menos un plan");
  }

  /*
  //verificar que se tenga seleccionado al menos un plan
  var idcollPlan = [];        
  $('.contListaPlanPre input:checkbox[class="selectCheck"]').each(function(){                               
       if($(this).is(':checked')) {  
          idcollPlan.push(this.value);
      }                     
  });
  // console.log(idcollPlan);

  if(idcollPlan.length>0){
    //verificar que se tenga seleccionado al menos una version de auto
    var element = $('.checktree ul li div.checked').length;    
    var arrIdsGralVersion = [];
    if(element>0){
      $(".checktree ul li div.checked").each(function(index, value) {
        idsGralVersion = "";
        arrIdsGralVersion.push($(this).children("input").val());        
      });
    }else{
      alertify.error("Seleccionar al menos una versión de algún modelo");
      return false;
    }
    // console.log(arrIdsGralVersion.join());
    // console.log(idcollPlan.join());
    var htmlOriginal = showLoading('btnGuardarPlanPre');    
    var params = {funct: 'crearPlanesDesdePre', ids:idcollPlan.join(), gralVersId:$("#planes_pre_verId").val()};

    var url ='planestreeview.php';
    var arrParams = [];
    arrParams[0] = {"name":"arrIdsPlan", "val":idcollPlan.join()};
    arrParams[1] = {"name":"arrIdsGralVersion", "val":arrIdsGralVersion};    
    postDinamico(url, arrParams);  
  }else{
    alertify.error("Seleccionar al menos un plan");
  }
  */
}

//>>>>Para las promociones - modelos
//Cambiar 
$(function(){
  $("#id_promo").change(function(){   
    $('.checktree li ul input').removeClass("active");
    $('.checktree li ul input').prop('checked', false); //hijos
    $('.checktree li input').prop('checked', false); //Padres
    //Limpiar contendedores
    $("#cont_caracteristicas").html("");
    $("#cont_verDetallePopup").html("");

    if($(this).val()!=""){
        $(".cont_modelos_versiones").show();

        //Obtener ids de las versiones por el id del promo seleccionado          
        var params = {funct: 'obtVersionPromoPorIdPadre', idPadre:$(this).val()};
        ajaxData(params, function(data){
          // console.log(data);                
          if(data.success==true){
            $.each(data.colVersiones, function(i,v){
                var target = "#vers_"+v.gralVersId;
                $(target).prop('checked', true);
                $(target).addClass('active');
            });
          }else{
            $('.checktree li ul input').removeClass("active");
            $('.checktree li ul input').prop('checked', false); //hijos
            $('.checktree li input').prop('checked', false); //Padres          
          }
        });

        //Obtener datos de la promo por su id      
        var idVersionPromo = $(this).val();              
        var paramsPromo = {funct: 'obtPromocionPorId', idPromocion:idVersionPromo};
        ajaxData(paramsPromo, function(data){    
            // console.log(data);        
            var html = '<div id="caracpromo_'+idVersionPromo+'" style="display:none;">'+data.caracteristicas+'</div>';
            $("#cont_caracteristicas").html(html);
            var link = '<a href="javascript:void(0);" class="badge badge-light my_popup_open" onclick="verDetallePopup('+idVersionPromo+', \'promo\');">Ver detalle</a>';
            $("#cont_verDetallePopup").html(link);
        });
    }else{
      $(".cont_modelos_versiones").hide();
    }    
  });
});
//obtener los ids de las versiones para crear los planes seleccionados
function salvarPromosVersionesModelos() {
  var idPromo = $("#id_promo").val();
  if(idPromo != ""){
    var element = $('.checktree li ul input.active').length;    
    var arrIdsGralVersion = [];
    if(element>0){
      $('.checktree li ul input.active').each(function(index, value) {        
        arrIdsGralVersion.push($(this).val());
      });
    }
    // else{
    //   alertify.error("Seleccionar al menos una versión de algún modelo");
    //   return false;
    // }

    console.log(arrIdsGralVersion.join());
    var htmlOriginal = showLoading('btnGuardarPlanPre');

    var url ='promostreeview.php';
    var arrParams = [];
    arrParams[0] = {"name":"arrIdsPromo", "val":idPromo};
    arrParams[1] = {"name":"arrIdsGralVersion", "val":arrIdsGralVersion};    
    postDinamico(url, arrParams);

  }else{
    alertify.error("Seleccionar al menos una promoción");
  }
}


//Salvar el orden de los modelos y mostrarlos ordenados por su id
function salvarOrdenModelo(){
    var dataIds = $("#sortable").sortable("toArray");    

    var url ='gamamodelos.php';
    var arrParams = [];
    arrParams[0] = {"name":"arrIdsModelos", "val":dataIds.join()};
    arrParams[1] = {"name":"ordenModelos", "val":1};    
    postDinamico(url, arrParams);
}

//Salvar el orden de las versiones y mostrarlos ordenados por su id
function salvarOrdenVersion(){
    var dataIds = $("#sortable").sortable("toArray");
    
    var url ='versiones.php';
    var arrParams = [];
    arrParams[0] = {"name":"arrIdsVersiones", "val":dataIds.join()};
    arrParams[1] = {"name":"ordenVersiones", "val":1};
    postDinamico(url, arrParams);
}



//>>>Para el borrado multiple
var arrIdsBorrarTmp = [];
var arrIdsBorrarFinal = [];
$(function(){
  $('.selDelMul').on('change', function(){
      var v = $(this).val();
      var value = (v == 1)?0:1;
      $(this).val(value);
  });
  //Obtener aquellos por eliminar
  $('#borradoMultiple').on('click', function(){
      arrIdsBorrarTmp = [];
      arrIdsBorrarFinal = [];
      $('input:checkbox[class="selDelMul"]').each(function(){                                   
          if($(this).is(':checked')) {
              var idCheck = $(this).attr("idCheck");
              arrIdsBorrarTmp[idCheck] = idCheck;              
          }
      });
      //Limpiar arreglo            
      if(arrIdsBorrarTmp.length>0){
          $.each(arrIdsBorrarTmp, function(i,v){
            if(typeof v != "undefined"){
              arrIdsBorrarFinal.push(v);
            }            
          });
      }
      
      //comprobar si existe algun elemento por borrar
      if(arrIdsBorrarTmp.length>0){              
        alertify.confirm("<strong>&#191Esta seguro que desea eliminar el(los) registro(s) seleccionado(s)?</strong>", function(){
            // console.log(arrIdsBorrarFinal);
            var idVistaCheck = (typeof $("#idVistaCheck").val() != "undefined") ?$("#idVistaCheck").val() :"";            
            var activazonaid = 0;
            if(idVistaCheck=="v_galversiones"){
              activazonaid = $("#idGM").val(); // es la variable activazonaid
            }
            var paramsBorrado = {funct: 'BorradoMultiple', idVistaCheck:idVistaCheck, idsCheckBorrado:arrIdsBorrarFinal.join(), activazonaid:activazonaid};
            ajaxData(paramsBorrado, function(dataBorrado){    
                if(dataBorrado.success==true){
                   console.log(dataBorrado);

                   //Borra galeria general de los modelos 
                   if(idVistaCheck=="v_galmodelos"){
                      galeriasModeloGrid.refresh();
                      galeriasModeloGrid.commit();
                      alertify.success("Proceso finalizado correctamente");
                   }
                   //Borra galeria general de los modelos 
                   if(idVistaCheck=="v_galversiones"){
                      versionZonaActivaGrid.refresh();
                      versionZonaActivaGrid.commit();
                      alertify.success("Proceso finalizado correctamente");
                   }
                }
                else{
                  alertify.error("No fue posible borrar el(los) registro(s), intente después");
                }                
            });            
        },function(){
            $('input:checkbox[class="selDelMul"]').each(function(){
                $(this).prop("checked",false);
                $(this).val(0);
                // $("#btnSeleccionarTodo").val("Seleccionar todo");
                $("#btnSeleccionarTodo").show();
                $("#btnDeseleccionarTodo").hide();
            });
        }).set({labels:{ok:'Aceptar', cancel: 'Cancelar'}, padding: false});  
      }else{
         alertify.error("No hay registro(s) para borrar"); 
      }
  });  
  // Seleccionar todo
  /*$('#btnSeleccionarTodo').on('click', function(){
      $(".selDelMul").each(function(i,v) {
        var vv = $(this).val();
        var value = (vv == 1)?0:1;
        $(this).val(value);  

        if(value == 1){
          $(this).prop('checked', true);
          $("#btnSeleccionarTodo").val("Deseleccionar todo");
        }else{
           $(this).prop('checked', false);
           $("#btnSeleccionarTodo").val("Seleccionar todo");
        }
      });
  });*/
  $('#btnSeleccionarTodo').on('click', function(){
      $(".selDelMul").each(function(i,v) {        
        $(this).val(1);
        $(this).prop('checked', true);        
      });
      $("#btnSeleccionarTodo").hide();
      $("#btnDeseleccionarTodo").show();
  });
  $('#btnDeseleccionarTodo').on('click', function(){
      $(".selDelMul").each(function(i,v) {        
        $(this).val(0);
        $(this).prop('checked', false);        
      });
      $("#btnSeleccionarTodo").show();
      $("#btnDeseleccionarTodo").hide();
  });
});
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//>>>>>>>>>>>>>>>>>>>>METODOS GENERALES>>>>>>>>>>>>>>>>>>>>
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

//metodo para abre llamadas ajax
function ajaxData(data, response){
  $.ajax({
      type: 'GET',
      dataType: 'jsonp',
      data: data,
      jsonp: 'callback',
      url: '../ajaxcall/ajaxFunctions.php',
      beforeSend: function () {
          //$("#imgLoadSave").show();
      },
      complete: function () {
      },
      success: function (data) {
          //console.log(data);
          response(data);
      },
      error: function () {
      }
  });
}

//metodo para subir imagenes y esperar el collback
function ajaxDataImg(url, data, response){
  $.ajax({
    type: "POST",
    url: url,
    data: data,
    cache: false,
    contentType: false,
    processData: false,
    success: function (rponse) {      
      response(JSON.parse(rponse));      
    },
    error: function () {
      console.log("Error");
    }
  });
}


//sirve para redirecionar y tomar el filtro correspondiente
function obtenervalorfiltro(control)
{
  var value = control.value;
  if(value!='')
     location.href="catalogos.php?catalog="+value+"";
}

//mostrar loading
function loadingAjax(target){
  html = '<div class="loadImg"><img src="../images/loading.gif" height="16" /></div>';
  $("#"+target).html(html);
}

//Formatea el campo
function muestraValorMoneda(idInput, valor)
{
    $("#"+idInput).val(accounting.formatColumn([valor], ""));
}

//metodo para agregar un post dinamico
function postDinamico(url, arrParams){  
  var form = $('<form/></form>');              
  form.attr("action", url);
  form.attr("method", "POST");
  form.attr("style", "display:none;");
  
  if(arrParams.length>0){
    $.each(arrParams, function(i,v){    
      console.log(v);
      input = $("<input></input>").attr("type", "hidden").attr("name", v.name).val(v.val);
      form.append(input);
    });  
    $("body").append(form);  
    // submit form
    form.submit();
    form.remove();
  }
}

//Metodo para poner loading cuando se presionan los botones que hacen accion sobre la DB
function showLoading(target){
  htmlOriginal = $("#"+target).parent().html();
  html = '<div class="loadImg"><img src="../images/loading.gif" height="16" /></div>';
  $("#"+target).hide();
  $("#"+target).parent().append(html);
    //Se retorna el html original para poder ocultar el loading mas tarde
    return htmlOriginal;
}

//Funcion para ocultar loading. Parametros target=id del elemento con loading, htmlOriginal=html obtenido de showLoading
function hideLoading(target, htmlOriginal)
{
  //Se agrega el html al padre del elemento target
  $("#"+target).parent().html(htmlOriginal);
}

function mostrarOjo(id){
  if($("#"+id).val())
    $("#eye-"+id).show();
  else
    $("#eye-"+id).hide();
}

function mostrarPassword(id)
{
  console.log("pika");
  $("#"+id).attr('type','text');
}

function ocultarPassword(id)
{
  $("#"+id).attr('type','password');
}

//Obtener extension de un archivo
function getFileExtension(filename) {
  return (/[.]/.exec(filename)) ? /[^.]+$/.exec(filename)[0] : undefined;
}

//Obtener parametro dada una url
function getParameterByName(name, url) {
  if (!url) url = window.location.href;
  name = name.replace(/[\[\]]/g, "\\$&");
  var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
  results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, " "));
}

