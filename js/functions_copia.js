/*
 * Date: 5/05/2017
 * Actualizado: 28/12/2017
 * Funciones generales y especificas de javascript
 */
//Inicio variables del mapa
var origenLatLong = {lat: 19.0434157, lng: -98.1991739}; //Inizializar por defecto
var destinoLatLong = {lat: 19.0434157, lng: -98.1971739}; //Inizializar por defecto
var map;
var markers = [];
var marcadorOrigen;
var marcadorDestino;
//parametros fancybox por defecto
var paramsFancy = {
          autoDimensions: false,
          padding : 30,
          width : 900,
          height : 900,
          autoScale : true,
          closeBtn : true,
          closeClick  : false,
        };

//Url para el archivo ajax que ocupa el autocomplete
var urlAutocomplete = "../ajaxcall/ajaxFunctions.php?";


$(document).ready(function(){
    $('input[name=\"activo\"]').click(function() {
        var v = $(this).val();
        var value = (v == 1) ? '0' : '1';
        $('#activo').val(value);        
    });

    if($("#formRegGamaModelo").length){
    //Validar formulario registro gama de modelo
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
  
  $("#fechaFE").attr("readonly", "readonly");
  $("#fechaComp").attr("readonly", "readonly");
 
        if($("#btResponderAclaracion").length){
        $("#btResponderAclaracion").fancybox({
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
                 $('#formResponderAclaracion')[0].reset();
              $("#comentarios").val("");
              $("#comentarios").text("");
            }
         });
        }
        if($("#btReasignarAclaracion").length){
        $("#btReasignarAclaracion").fancybox({
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
                 $('#formResponderAclaracion')[0].reset();
              $("#comentarios").val("");
              $("#comentarios").text("");
            }
         });
        }
        if($("#btAsignarAclaracion").length){
        $("#btAsignarAclaracion").fancybox({
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
        if($("#btAsignarGarantia").length){
        $("#btAsignarGarantia").fancybox({
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
        if($("#btnFechaCompromiso").length){
        $("#btnFechaCompromiso").fancybox({
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
        if($(".fancybox-thumb").length){
	$(".fancybox-thumb").fancybox({
		prevEffect	: 'none',
		nextEffect	: 'none',
		helpers	: {
			title	: {
				type: 'outside'
			},
			thumbs	: {
				width	: 50,
				height	: 50
			}
		}
	});
    }
      if($("#btnVerDetalleCliente").length){
      $("#btnVerDetalleCliente").fancybox({
        prevEffect  : 'none',
        nextEffect  : 'none',
        helpers : {
          title : {
            type: 'outside'
          },
          thumbs  : {
            width : 50,
            height  : 50
          }
        }
      });
    }
    if($("#btnVerDocumentosCliente").length){
      $("#btnVerDocumentosCliente").fancybox({
        prevEffect  : 'none',
        nextEffect  : 'none',
        helpers : {
          title : {
            type: 'outside'
          },
          thumbs  : {
            width : 50,
            height  : 50
          }
        }
      });
    }
    if($("#btnVerHistorialAtenciones").length){
      $("#btnVerHistorialAtenciones").fancybox({
        prevEffect  : 'none',
        nextEffect  : 'none',
        helpers : {
          title : {
            type: 'outside'
          },
          thumbs  : {
            width : 50,
            height  : 50
          }
        }
      });
    }
    if($(".btnEnviarMensaje").length){
        $(".btnEnviarMensaje").fancybox({
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
        if($(".btnVerRequisitos").length){
        $(".btnVerRequisitos").fancybox({
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
        if($(".btnImagenPrototipo").length){
        $(".btnImagenPrototipo").fancybox({
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
        if($("#btn-mostrarcambiarpass").length){
        $("#btn-mostrarcambiarpass").fancybox({
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
                $('#formCambiarPass')[0].reset();
                $(".glyphicon-eye-open").hide();
            }
         });
        }
        if($(".btnEditarPuesto").length){
        $(".btnEditarPuesto").fancybox({
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
        if($(".btnEditarColaborador").length){
        $(".btnEditarColaborador").fancybox({
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
        if($(".btnVerPermisosDocCI").length){
        $(".btnVerPermisosDocCI").fancybox({
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
            closeBtn : true,
            closeClick  : false,
            helpers : {
                 overlay : {closeClick: true}
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
        // if($(".btnElimDocCliente").length){
        // $(".btnElimDocCliente").fancybox({
        //     autoDimensions: false,
        //   padding : 30,
        //   width : 900,
        //   height : 900,
        //   autoScale : true,
        //   closeBtn : true,
        //   closeClick  : false,
        //      helpers : {
        //          overlay : {closeClick: true}
        //      },
        //      beforeLoad: function() {
        //     }
        //  });
        // }
    /* FIN FANCYBOX */
    /* INICIO AUTOCOMPLETES */
      //Si existe el input inicializar autocomplete
        if($("#idMomento").length || $("#mensajesview").length){
        $("#campoBuscar").autocomplete({
            source: "",
            minLength: 3,
            search: function( event, ui ) {
              $("#idCliente").val('');
              $("#rowMensajeClienteSeleccionado").hide();
                var campoBuscar = $("#campoBuscar").val();
                rutaFinal = urlAutocomplete+"funct=buscarCliente2&campo="+$("#buscarPor").val()+"&valor="+campoBuscar;
                $(this).autocomplete( 'option', 'source', rutaFinal);
            },
            select: function (event, ui) {
              if($("#buscarPor").val() == "numContrato")
              {
                  $("#campoBuscar").val(ui.item.numContrato);
              }
              else
              {
                  $("#campoBuscar").val(ui.item.nombre);
              }
              $("#idCliente").val(ui.item.idUsuario);
              $("#rowMensajeClienteSeleccionado").show();
              $("#numContratoClienteSeleccionado").html(ui.item.numContrato);
            $("#nombreClienteSeleccionado").html(ui.item.nombre);
            $("#emailClienteSeleccionado").html(ui.item.email);
            $("#telefonoClienteSeleccionado").html(ui.item.telefono);
              return false;
            },
            change: function(event, ui) { // <=======
              if($("#idCliente").val() == "")
              {
                  $("#campoBuscar").focus();
                  $("#campoBuscar").val('');
              }
           }
        })
        .autocomplete( "instance" )._renderItem = function( ul, item ) {
            return $( "<li>" )
              .append( "<div>" + item.nombre + "-" +item.numContrato+ "</div>" )
              .appendTo( ul );
        };
    }
    /* FIN AUTOCOMPLETE */
    /*INICIO LIST BOX*/
    if($("#listaPuestos").length){
      $("#listaPuestos").bootstrapDualListbox({
          nonSelectedListLabel: 'Non-selected',
          selectedListLabel: 'Selected',
          preserveSelectionOnMove: 'moved',
          moveOnSelect: false
      });
    }
    /*FIN LIST BOX*/
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
            delay:5,
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
  
  //Recargar el grid de zonas
  $(".btnMenu6").click(function(){
      versionZonaGrid.refresh();
      versionZonaGrid.commit();
  });
    
});

//mostrar loading
function loadingAjax(target){
    html = '<div class="loadImg"><img src="../images/loading.gif" height="16" /></div>';
    $("#"+target).html(html);
}

//FUNCION para abreviar llamadas ajax
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


//>>>
//>>>METODOS FUERA DEl $(document).ready<<<<<<
//>>>

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
        params = {funct: 'ActivaDesactivaVersionPlan', id: id, valor:valor};
        ajaxData(params, function(data){
            if(data.success)
            {
                console.log("success");
                versionPlanGrid.refresh();
                versionPlanGrid.commit();
                alertify.success("Actualizado correctamente");
            }
            else
            {
                console.log("Else");
                alertify.error("Error, intente mas tarde");
            }
        });
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

function muestraValorMoneda(idInput, valor)
{
    $("#"+idInput).val(accounting.formatColumn([valor], ""));
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
          tinymce.init({
                selector: "#vp_carac",
                theme: "modern",
                plugins: [
                    ["advlist autolink link image lists preview hr pagebreak"],
                    ["searchreplace wordcount visualblocks visualchars code media"],
                    ["save contextmenu directionality emoticons paste"]
                ],
                height : "230"
             });
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
                  tinymce.init({
                        selector: "#vp_carac",
                        theme: "modern",
                        plugins: [
                            ["advlist autolink link image lists preview hr pagebreak"],
                            ["searchreplace wordcount visualblocks visualchars code media"],
                            ["save contextmenu directionality emoticons paste"]
                        ],
                        height : "230"
                     });
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
            params = {
              funct: 'guardarVersionPlan', 
              idVersionGral: $("#idVersionGral").val(), 
              idVersionPlan: $("#idVersionPlan").val(),
              idTipoPlan: $("#vp_idtipoplan").val(),
              caracteristicas: $("#vp_carac").val()
            };
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
    if(verReqId == 0)
    {
        $("#idVersionReq").val(0);
        $("#vr_concepto").val("");
        var htmlOriginal = showLoading('btnGuardarReq');
        setTimeout(function(){
          $("#div_vr_carac").html('<textarea class="form-control required" name="vr_carac" id="vr_carac" rows="4"></textarea>');
          // hideLoading('contenidoRec',html);
          tinymce.init({
                selector: "#vr_carac",
                theme: "modern",
                plugins: [
                    ["advlist autolink link image lists preview hr pagebreak"],
                    ["searchreplace wordcount visualblocks visualchars code media"],
                    ["save contextmenu directionality emoticons paste"]
                ],
                height : "230"
             });
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
                setTimeout(function(){
                  $("#div_vr_carac").html('<textarea class="form-control required" name="vr_carac" id="vr_carac" rows="4">'+data.caracteristicas+'</textarea>');
                  // hideLoading('contenidoRec',html);
                  tinymce.init({
                        selector: "#vr_carac",
                        theme: "modern",
                        plugins: [
                            ["advlist autolink link image lists preview hr pagebreak"],
                            ["searchreplace wordcount visualblocks visualchars code media"],
                            ["save contextmenu directionality emoticons paste"]
                        ],
                        height : "230"
                     });
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
            params = {
              funct: 'guardarVersionReq', 
              idVersionGral: $("#vr_idVersionGral").val(), 
              idVersionReq: $("#idVersionReq").val(),
              concepto: $("#vr_concepto").val(),
              caracteristicas: $("#vr_carac").val()
            };
            ajaxData(params, function(data){
                if(data.success)
                {
                  console.log("success");
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
function mostrarFancyAgregarColor(verColorId, color, imagenAuto, imagenColor)
{
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
    alertify.confirm("<strong>&#191Est&aacute; seguro que desea eliminar este color?</strong>", function(){
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
function mostrarFancyEditarImgGaleria(zonaVersId, idImagen, titulo, descripcion, imagen)
{
    descripcion = $("#descripcion_img_"+idImagen).val();
    titulo = $("#titulo_img_"+idImagen).val();
    $("#imagen_imggal").attr("src",imagen);
    $("#idimagen_imggal").val(idImagen);
    $("#zonaVersId_imggal").val(zonaVersId);
    $("#titulo_imggal").val(titulo);
    var htmlOriginal = showLoading('btnGuardarImgGal');
    setTimeout(function(){
      $("#div_desc_imggal").html('<textarea class="form-control" rows="4" name="descripcion_imggal" id="descripcion_imggal">'+descripcion+'</textarea>');
      // hideLoading('contenidoRec',html);
      tinymce.init({
            selector: "#descripcion_imggal",
            theme: "modern",            
            plugins: [
                ["advlist autolink link image lists preview hr pagebreak"],
                ["searchreplace wordcount visualblocks visualchars code media"],
                ["save contextmenu directionality emoticons paste"]
            ],
            height : "170"
         });
        hideLoading('btnGuardarImgGal', htmlOriginal);
      // hideLoading('btnGuardarReco',htmlOriginal2);
    }, 1000);
    // $("#descripcion_imggal").val(descripcion);
}
function guardarImgGal()
{
    tinymce.triggerSave();
    if($("#formImagenGal").valid())
    {
        params = {
              funct: 'guardarImagenGaleria',
              zonaVersId: $("#zonaVersId_imggal").val(),
              idImagen: $("#idimagen_imggal").val(),
              titulo: $("#titulo_imggal").val(),
              descripcion: $("#descripcion_imggal").val(),
        };
        ajaxData(params, function(data){
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
//sirve para redirecionar y tomar el filtro correspondiente
function obtenervalorfiltro(control)
{
  var value = control.value;
  if(value!='')
     location.href="catalogos.php?catalog="+value+"";
}






function obtenervalorfiltro2(control)
{
  var value = control.value;
  catalog = $("#selectCatalog").val();
     location.href="catalogos.php?catalog="+catalog+"&tipoP="+value;
}
function obtenervalorfiltro3(control)
{
  var value = control.value;
  catalog = $("#selectCatalog").val();
     location.href="catalogos.php?catalog="+catalog+"&tipoPlano="+value;
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
//Acciones necesarias para cuando se muestra el fancy responder aclaracion
function mostrarResponderAclaracion(idAclaracion)
{
    $("#idAclaracion").val(idAclaracion);
}
//Acciones necesarias para cuando se muestra el fancy reasignar aclaracion
function mostrarReasignarAclaracion(idAclaracion)
{
    $("#idAclaracionReasignar").val(idAclaracion);
}
//Funcion para guardar el id del jefe del area cuando se selecciona un area en reasignar aclaracion
function recuperaColaboradorReasignar()
{
    var element = $("#idArea").find('option:selected');
    var idColaborador = element.attr("idColaborador");
    console.log(idColaborador);
    $("#idJefeArea").val(idColaborador);
}
//Funcion para las acciones cuando cambia el select de procesos en momento
function cambioProcesoMomento(idProceso)
{
  $("#msgClientesEncontrados").hide();
  $("#msgNumClientes").html('');
  $("#campoBuscar").val('');
  $("#rowMensajeClienteSeleccionado").hide();
  if(idProceso != "")
  {
    if(idProceso == 8)
    {
        $("#rowSelectClientes").hide();
        $("#rowBuscarPor").hide();
        $("#rowFraccionamientos").show();
        $("#rowCampoBuscar").hide();
        $("#idCliente").attr("required",false);
        $("#campoBuscar").attr("required", false);
        $("#idDesarrollo").attr("required", true);
        $("#rowCheckMomento").show();
        $("#rowImagenMomento").show();
        $("#rowYoutubeMomento").hide();
        if($("#youtubeMomento").val() != "")
        {
            $("#rowImagenMomento").hide();
            $("#rowYoutubeMomento").show();
        }
    }
    else
    {
        $("#rowBuscarPor").show();
        $("#rowFraccionamientos").hide();
        $("#rowCampoBuscar").show();
        $("#rowSelectClientes").show();
        var select = '<select class="form-control" id="idCliente" name="idCliente" onchange="seleccionarCliente(this.value)">'+
                        '<option value="">Seleccione...</option>'+
                    '</select>';
        $("#idCliente").html(select);
        $("#idCliente").attr("required",true);
        $("#campoBuscar").attr("required", true);
        $("#idDesarrollo").attr("required", false);
        $("#rowCheckMomento").hide();
        $("#rowImagenMomento").show();
        $("#rowYoutubeMomento").hide();
    }
  }
}
//Funcion para buscar clientes cuando se agrega o edita un momento
function buscarClienteMomento(valor)
{
    $("#rowMensajeClienteSeleccionado").hide();
    $("#msgClientesEncontrados").hide();
    $("#msgNumClientes").html('');
    var params = {
        funct: 'buscarCliente',
        campo: $("#buscarPor").val(),
        valor: valor
    };
    //Llamada a ajax para generar html del select con los clientes encontrados
    ajaxData(params, function(data){
        $("#msgClientesEncontrados").show();
       if(data[0].res == 'ok')
       {
           console.log(data[0]);
           $("#msgNumClientes").html(data[0].usuarios.length);
           var select = '<select class="form-control" id="idCliente" name="idCliente" required="" onchange="seleccionarCliente(this.value)">';
           select += '<option value="">Seleccione...</option>';
          data[0].usuarios.forEach(function(usuario){
            select += '<option value="'+usuario.idUsuario+'" numContrato="'+usuario.numContrato+'" email="'+usuario.email+'" telefono="'+usuario.telefono+'" nombre="'+usuario.nombre+'">'+usuario.numContrato+' - '+usuario.nombre+'</option>';
          });
           select += '</select>';
           $("#idCliente").html(select);
       }
       else
       {
          $("#msgNumClientes").html(0);
       }
    });
}
//Funcion de acciones cuando en momento cambia el select buscarPor
function cambioBuscarPor(valor)
{
  if(valor == "numContrato")
  {
    $("#campoBuscar").attr("placeholder","Escriba un num de contrato");
  }
  else
  {
    $("#campoBuscar").attr("placeholder","Escriba un nombre de cliente");
  }
    $("#msgClientesEncontrados").hide();
    $("#msgNumClientes").html('');
    $("#campoBuscar").val('');
    var select = '<select class="form-control" id="idCliente" name="idCliente" onchange="seleccionarCliente(this.value)">'+
                        '<option value="">Seleccione...</option>'+
                    '</select>';
    // $("#idCliente").html(select);
    $("#rowMensajeClienteSeleccionado").hide();
}
//funcion de acciones cuando se selecciona un cliente, modulo agregar o editar momento
function seleccionarCliente(idUsuario)
{
    $("#rowMensajeClienteSeleccionado").hide();
    if(idUsuario != "")
    {
        $("#rowMensajeClienteSeleccionado").show();
        var element = $("#idCliente").find('option:selected');
        var idColaborador = element.attr("idColaborador");
        $("#numContratoClienteSeleccionado").html(element.attr("numContrato"));
        $("#nombreClienteSeleccionado").html(element.attr("nombre"));
        $("#emailClienteSeleccionado").html(element.attr("email"));
        $("#telefonoClienteSeleccionado").html(element.attr("telefono"));
    }
    else
    {
        $("#idCliente").focus();
    }
}
//onchange #enlace
function actualizaiframe(enlace)
{
  //obtener el id del video (paramtro v)
  var embed = getParameterByName("v", enlace);
  console.log(embed);
  //si es nulo, el parametro no existe por lo tanto la url es incorrecta
  if(embed == null)
  {
    //aviso si la url es incorrecta
    alertify.error("Por favor introduzca un enlace de youtube");
    //alert("Por favor introduzca un enlace de youtube","Aviso");
    $("#enlace").val("");
    $("#enlace").focus();
     $("#idvideoyoutube").val("");
  }
  else 
  {
    if(embed == '')
    {
      //aviso si la url tiene parametro v pero su valor es vacio
      //alert("Por favor revisa que la url sea correcta","Aviso");
      alertify.error("Por favor introduzca una url correcta");
      $("#enlace").val("");
      $("#enlace").focus();
      $("#idvideoyoutube").val("");
    }
    else
    { 
      //si es correcto actualizar iframe
      $("#diviframe").html('<iframe type="text/html" src="http://www.youtube.com/embed/'+embed+'" frameborder="0" allowfullscreen></iframe>');
      $("#idvideoyoutube").val(embed);
    }
  }
}
//Acciones cuando cambia la opcion BuscarPor en el modulo busqueda de clientes
function cambioBuscarPorB(valor)
{
    //si se busca por fraccionamiento/desarrollo mostrar select con manzana, lote o edificio
    if(valor == "idDesarrollo")
    {
        $("#divLabelBuscarPorFracc").show();
        $("#divSelectBuscarPorFracc").show();
        $("#divMensajeFraccionamiento").show();
        $("#campoBuscar").attr("placeholder","Escriba una manzana");
    }
    else
    {
      if(valor == "numContrato")
      {
          $("#campoBuscar").attr("placeholder","Escriba un num de contrato");
      }
      else
      {
        $("#campoBuscar").attr("placeholder","Escriba un nombre de cliente");
      }
        $("#divLabelBuscarPorFracc").hide();
        $("#divSelectBuscarPorFracc").hide();
        $("#divMensajeFraccionamiento").hide();
    }
    // $("#campoBuscar").val("");
}
function cambioBuscar2PorB(valor)
{
    if(valor == "idManzana")
    {
      $("#campoBuscar").attr("placeholder","Escriba una manzana");
    }
    else if(valor == "noLote")
    {
      $("#campoBuscar").attr("placeholder","Escriba un num de lote");
    }
    else
    {
      $("#campoBuscar").attr("placeholder","Escriba un edificio");
    }
    $("#campoBuscar").val("");
}

//funcion cuando se abre el fancy de detalle de pagos del cliente -- modulo informacion del cliente
function verDetalleCliente()
{
  //Inicializar la tabla solo al primera vez
    if(!inicializada)
    {
        $('#tabla_busqueda').DataTable({
            scrollY: true,
            scrollX: true,
            searching: false,
            dom: 'Bfrtip',
            bInfo: true,
            responsive: true,
            "language": {
                "url": "../js/Spanish.json"
            },
            aaSorting: []
        });
        inicializada = true;
    }
}
//funcion cuando cambia un medio-- modulo informacion del cliente (seccion agregar atencion)
function cambiaMedio(idMedio)
{
  //Si el medio seleccionado es 0 (otro medio) mostrar textarea de otro medio
  // (modulo informacion del cliente - seccion agregar atencion) 
  if(idMedio == 0 && idMedio != "")
  {
      $("#rowOtroMedio1").show();
      $("#otroMedio").show();
      $("#otroMedio").addClass("required");
  }
  else
  {    
      $("#otroMedio").removeClass("required");
      $("#rowOtroMedio1").hide();
      $("#otroMedio").hide();
      $('label[for="otroMedio"]').hide();
  }
}
//funcion para guardar una atencion (modulo informacion del cliente - seccion agregar atencion) 
function guardarAtencion()
{
    $.validator.setDefaults({
      debug: true,
      success: "valid"
    });
    var otroMedio = '';
    //Verificar valores de que se enviaran
    if($("#idMedio").val() == 0)
    {
        otroMedio = $("#otroMedio").val();
    }
    //Si el formulario es valido
    if($("#formReportarAtencion").valid()){
          var params = {
            funct: 'agregarAtencion',
            idColaborador: $("#idColaborador").val(),
            idCliente: $("#idCliente").val(),
            idProceso: $("#idProceso").val(),
            idMedio: $("#idMedio").val(),
            otroMedio: otroMedio,
            descripcion : $("#descripcionAtencion").val()
          };
          //ajax para consultar los clientes
        ajaxData(params, function(data){
          //Veirifica si se inserto la aclaracion
         if(data.idAtencion > 0)
         {
            $("#atencionsuccess").show();
            $("#atencionwarning").hide();
            setTimeout(function(){
              $("#atencionsuccess").hide();
            }, 3000);
            limpiarFormularioAtencion();
         }
         else
         {
            $("#atencionwarning").show();
            $("#atencionsuccess").hide();
            setTimeout(function(){
              $("#atencionwarning").hide();
            }, 5000);
         }
      });
    }
    else
    {
      console.log("no validado");
    }
}
//Funcion para ver el historial de atenciones del cliente
function verHistorialAtenciones()
{
    var params = {
          funct: 'historialAtencionesCliente',
          idCliente: $("#idCliente").val()
      };
      //ajax para consultar las atenciones
      ajaxData(params, function(data){
          //Agrega la tabla con los resultados
            $("#datable_historial").html('');
            $("#datable_historial").html(data.tabla);
              //inicializar tabla
               $('#historial_atenciones').DataTable({
                    scrollY: true,
                    scrollX: true,
                    searching: false,
                    dom: 'Bfrtip',
                    bInfo: true,
                    responsive: true,
                    "language": {
                        "url": "../js/Spanish.json"
                    },
                    aaSorting: []
                });
               $('[data-toggle="tooltip"]').tooltip({html:true});   
      });
}
//Funcion para limpiar el formulario de atencion 
function limpiarFormularioAtencion()
{
    $("#otroMedio").removeClass("required");
    $("#rowOtroMedio1").hide();
    $("#otroMedio").hide();
    $('label[for="otroMedio"]').hide();
    $("#otroMedio").val("");
    $("#idProceso").val("");
    $("#idMedio").val("");
    $("#descripcionAtencion").val("");
    $("label.error").hide();
    $("label.valid").hide();
}
//Funcion que recibe array de usuarios y devuelve tabla de busqueda usuarios
function generaTablaBusquedaUsuarios(usuarios, opc)
{
    var tabla = '<table id="tabla_busqueda" class="table table-striped table-bordered table-condensed dataTable no-footer dt-responsive" role="grid" cellspacing="0" width="100%" >'+
                '<thead>'+
                    '<tr>'+
                        '<th>N&#176; Contrato <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>'+
                        '<th>Nombre <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>'+
                        '<th>Desarrollo <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>'+
                        '<th>Acci&oacute;n <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>'+
                    '</tr>'+
                '</thead>'+
                '<tbody>';
    usuarios.forEach(function(usuario){
      tabla += '<tr>'+
            '<td>'+usuario.numContrato+'</td>'+
            '<td>'+usuario.nombre+'</td>'+
            '<td>'+usuario.desarrollo+'</td>';
            if(opc == 1)
            {
                tabla += '<td><a class="cursorPointer" onclick="guardarBusquedaHistorico('+usuario.idUsuario+','+usuario.idVivienda+')">Ver</a></td>';
            }
            else
            {
              tabla += '<td style="text-align: center">';
              tabla += '<a class="cursorPointer" onclick="cargarInfoClienteParaEditar('+usuario.idUsuario+','+usuario.idVivienda+', \''+usuario.numContrato+'\', \''+usuario.nombre+'\')" title="Editar informacion del cliente"><img src="../images/icon_info.png" class="iconoDesactivar" ></a>&nbsp;&nbsp;';
              if( $("#permisoSubirDocs").val() == 1 )
              {
                tabla += '<a class="cursorPointer" onclick="cargarDocumentosCliente('+usuario.idUsuario+','+usuario.idVivienda+')" title="Subir documentos del cliente"><img src="../images/icon_archive.png" class="iconoDesactivar" ></a>';
              }
              tabla += '</td>';
            }
            tabla += '</tr>';
    });
    tabla += '</tbody>'+
            '</table>';
    return tabla;
}
//Funcion para cargar la informacion del cliente que se puede editar y ponerla en un div
function cargarInfoClienteParaEditar(idCliente, idVivienda, numContrato, nombre)
{
  $("#infoClienteEditar").hide();
  $("#cont_editar").hide();
    htmlOriginal = showLoading('infoClienteEditar');
    var params = {
          funct: 'consultaVivienda',
          idVivienda: idVivienda
      };
      //ajax para guardar historico y redireccionar
      ajaxData(params, function(data){
        setTimeout(function(){
          hideLoading('infoClienteEditar', htmlOriginal);
           if(data.success)
           {
                $("#idVivienda").val(idVivienda);
                $("#idCliente").val(idCliente);
                $("#infoClienteEditar").show();
                $("#cont_editar").hide();
                console.log(data.fechaEntregaVivienda);

                $("#fechaFE").val(data.fechaFirmaEscritura);
                $("#spanFechaEV").html(data.fechaEntregaVivienda);
                $("#spanFechaFE").html(data.fechaFirmaEscritura);
                $("#renta").val(data.renta);
                var renta = "No";
                if(data.renta == 1)
                {
                  renta = "Si";
                }
                $("#spanRenta").html(renta);
                $("#spanNombreCliente").html(nombre);
                $("#spanNumContrato").html(numContrato);
           }
           else
           {
              $("#cont_editar").html("Error cargando informacion");
              $("#cont_editar").show();
           }
         }, 1000);
      });
}
function cargarDocumentosCliente(idCliente, idVivienda)
{
    $("#idVivienda").val(idVivienda);
    $("#idCliente").val(idCliente);
    $("#infoClienteEditar").hide();
    $("#cont_editar").hide();
    $("#cont_editar").html("");
    $("#cont_editar_aux").show();
    htmlOriginal = showLoading('cont_editar_aux');
    //$("#cont_editar").load("subirdocscliente2.php?idVivienda="+idVivienda+"&idCliente="+idCliente);
    $("#cont_editar").load("subirdocscliente2.php?idVivienda="+idVivienda+"&idCliente="+idCliente,{},
      function(response, status, xhr) {
        hideLoading('cont_editar_aux', htmlOriginal);
        $("#cont_editar_aux").hide();
        if (status == "error") {
            var msg = "Error!, algo ha sucedido: ";
            $("#cont_editar").html(msg + xhr.status + " " + xhr.statusText);
        }
        else
        {
            console.log(status);
            $("#cont_editar").show();
        }
            $('[data-toggle="tooltip"]').tooltip({html:true});
        $('.dropdown-toggle').dropdown();
        
    });


}
function subirDocCliente(idDocumento, url, imgAnteriorEd, nomImg, tipo)
{
    file = $('input#archivoCli_'+idDocumento)[0].files[0];
    imgAnteriorEd = $("#docant_"+idDocumento).val();
    var n=Math.floor(Math.random()*111);
    var k = Math.floor(Math.random()* 10000000);
    var m = n+k;
    var ext = getFileExtension(file.name);
    var nomImg = idDocumento+"_"+m+"."+ext;
    console.log(nomImg);
    if(ext == "pdf" || ext == "doc" || ext == "docx" || ext == "txt")
    {
        htmlOriginal = showLoading('spanarchivocli_'+idDocumento);
        subirArchivosPorAjax(file, url, imgAnteriorEd, nomImg, tipo, function(){
    setTimeout(function(){
        if ($("#resultadoSubir").val().indexOf('se ha subido correctamente')!=-1) {
                    var urlDoc = $("#siteUrl").val()+'upload/docsclientes/'+nomImg;
            var params = {
              funct: 'editarDocumento',
              campo: 'urlDocumento',
              idDocumento: idDocumento,
                      valor: urlDoc
            };
             ajaxData(params, function(data){
              console.log(data);
              alertify.success($("#resultadoSubir").val());
                        // cargarDocumentosCliente($("#idCliente").val(), $("#idVivienda").val());
                        hideLoading('spanarchivocli_'+idDocumento, htmlOriginal);
                        $("#descargarArchivo_"+idDocumento).html('<a href="'+urlDoc+'" class="btn btn-default btn-file white" download="" title="Descargar" ><span class="glyphicon glyphicon-cloud-download" ></span></a>');
                        $("#eliminarArchivo_"+idDocumento).html('<a class="btn btn-primary btn-delete btn-default btn-file white btnElimDocCliente" onclick="eliminarDocumentosCliente('+idDocumento+', \''+nomImg+'\')" title="Eliminar" id="elimdoccliente_'+idDocumento+'"><span class="glyphicon glyphicon-remove-circle" ></span></a>');
                        $("#tooltip_estatus_"+idDocumento).html('<a data-toggle="tooltip" title="Ya se ha subido anteriormente este archivo, si lo sube nuevamente, se perdera el anterior." data-placement="top" ><span class="glyphicon glyphicon-info-sign"></span></a>');
                        $('[data-toggle="tooltip"]').tooltip({html:true});
                        $("#docant_"+idDocumento).val(nomImg);
              });
        }
        else
        {
                    alertify.error($("#resultadoSubir").val());
            console.log($("#resultadoSubir").val());
        }
        //$("#mensajecarga").html('<p>'+$("#resultadoSubir").val()+'</p>');
    }, 500);
        });
    }
    else
    {
        $("#resultadoSubir").val("El tipo de archivo ."+ext+" no esta permitido");
    }
}
function eliminarDocumentosCliente(idDocumento, nombreDoc)
{
    $("#idDocumentoEliminar").val(idDocumento);
    $("#nombreDocumentoEliminar").val(nombreDoc);
    var pre = document.createElement('pre');
    //custom style.
    //show as confirm
    alertify.confirm("<strong>&#191Esta seguro que desea eliminar este documento?</strong>", function(){
            confirmEliminarDoc(idDocumento, nombreDoc);
        },function(){
          $("#idDocumentoEliminar").val("");
    $("#nombreDocumentoEliminar").val("");
            // alertify.error('Cancelar');
        }).set({labels:{ok:'Aceptar', cancel: 'Cancelar'}, padding: false});
}
function confirmEliminarDoc(idDocumento, nombreDoc)
{
    console.log(idDocumento+' '+ nombreDoc);
    if(idDocumento != "" && nombreDoc != "")
    {
        htmlOriginal = showLoading('elimdoccliente_'+idDocumento);
        var params = {
              funct: 'editarDocumento',
              campo: 'urlDocumento',
              idDocumento: idDocumento,
              valor: '',
              nombreAnt: nombreDoc
            };
             ajaxData(params, function(data){
              $("#btnCancelElimDoc").click();
              console.log(data);
              alertify.success("Se ha eliminado el documento.");
              // cargarDocumentosCliente($("#idCliente").val(), $("#idVivienda").val());
               hideLoading('elimdoccliente_'+idDocumento, htmlOriginal);
              $("#descargarArchivo_"+idDocumento).html('');
              $("#eliminarArchivo_"+idDocumento).html('');
              $("#tooltip_estatus_"+idDocumento).html('<a data-toggle="tooltip" title="No se ha subido este archivo." data-placement="top" ><span class="glyphicon glyphicon-info-sign"></span></a>');
              $('[data-toggle="tooltip"]').tooltip({html:true});
              $("#docant_"+idDocumento).val("");
              });
    }
}
function subirPlano(idPlano, url, imgAnteriorEd, nomImg, tipo)
{
    file = $('input#plano_'+idPlano)[0].files[0];
    imgAnteriorEd = $("#planoant_"+idPlano).val();
    var n=Math.floor(Math.random()*111);
    var k = Math.floor(Math.random()* 10000000);
    var m = n+k;
    var ext = getFileExtension(file.name);
    var nomImg = idPlano+"_"+m+"."+ext;
    console.log(nomImg);
    if(ext == 'jpg' ||  ext == 'jpeg' || ext =='gif' || ext == 'png' || ext == 'tif' || ext == 'tiff' || ext == 'bmp')
    {
        subirArchivosPorAjax(file, url, imgAnteriorEd, nomImg, tipo, function(){
            setTimeout(function(){
              if ($("#resultadoSubir").val().indexOf('se ha subido correctamente')!=-1) {
                  var params = {
                    funct: 'editarPlano',
                    campo: 'nombreImagen',
                    idPlano: idPlano,
                    valor: nomImg
                  };
                   ajaxData(params, function(data){
                    if(data.success)
                    {
                        alertify.success($("#resultadoSubir").val());
                        if(data.resv > 0)
                        {
                            alertify.success("Se actualizo el plano en "+data.resv+" viviendas");
                        }
                    }
                    console.log(data);
                    planosGrid.refresh();
                    planosGrid.commit();
                    });
              }
              else
              {
                  alertify.error($("#resultadoSubir").val());
                  console.log($("#resultadoSubir").val());
              }
              //$("#mensajecarga").html('<p>'+$("#resultadoSubir").val()+'</p>');
          }, 500);
        });
    }
    else
    {
        $("#resultadoSubir").val("El tipo de archivo ."+ext+" no esta permitido");
    }
}
function presionarEnter(event, vista) {
    if (event.which == 13 || event.keyCode == 13) {
        //code to execute here
        event.preventDefault();
        switch(vista) {
            case 'busqueda2':
                busquedaClientes(2);
                break;
            case 'busqueda':
                busquedaClientes(1);
                break;
            default:
                console.log("Presionar enter"+true);break;
        }
    }
    else
    {
      //console.log("Presionar enter"+false);
    }
}
//Funcion para guardar el historico de la busqueda del cliente y redireccionar
function guardarBusquedaHistorico(idCliente, idVivienda)
{
    var url = 'infocliente.php?idVivienda='+idVivienda+'&idCliente='+idCliente;
    var params = {
          funct: 'guardarBusquedaHistorico',
          idCliente: idCliente
      };
      //ajax para guardar historico y redireccionar
      ajaxData(params, function(data){
         console.log(data.idBusquedaHis);
         window.location.href = url;
      });
}
//Funcion para mostrar u ocultar el enlace de youtube en momentos
function cambioCheckMomento()
{
      if( $("#radioMomentoY").prop("checked") ){
    //if( $('#checkMomento').prop('checked') ) {
        $("#rowYoutubeMomento").show();
         $("#youtubeMomento").val('');
        $("#rowImagenMomento").hide();
        $("#rowMensajeImagenMomento").hide();
        $("#scrolly").hide();
        $("#imagenMomento").attr("required",false);
        $("#youtubeMomento").attr("required",true);
    }
    else
    {
        $("#rowYoutubeMomento").hide();
        $("#rowImagenMomento").show();
        $("#youtubeMomento").val('');
        if($("#idMomento").val() > 0)
        {
            if($("#imagenMomento").attr("src") != "")
            {
              $("#rowMensajeImagenMomento").show();
              $("#scrolly").show();
            }
        }
        $("#imagenMomento").attr("required",true);
        $("#youtubeMomento").attr("required",false);
    }
}
//Modulo mensajes, cambia buscar por
function cambiaMenBuscarPor(valor)
{
   if(valor == "numContrato")
   {
      $("#valor").attr("placeholder","Escriba un num de contrato");
      $("#valor").attr("required",true);
   }
   else if(valor == "nombre")
   {
      $("#valor").attr("placeholder","Escriba un nombre de cliente");
      $("#valor").attr("required",true);
   }
   else
   {
      $("#valor").attr("placeholder","No utilizar");
      $("#valor").attr("required",false);
   }
}
//modulo mensajes, muestra fancy enviar mensaje
function muestraEnviarMensaje(numContrato, nombre, idCliente)
{
    if(numContrato == 'espe')//Si se busco un cliente especifico se asignan los datos
    {
      numContrato = $("#numContratoClienteSeleccionado").text();
      nombre = $("#nombreClienteSeleccionado").text();
      idCliente = $("#idCliente").val();
    }
    $('#formEnviarMensaje')[0].reset();
    $("#numContratoEM").html(numContrato);
    $("#nombreClienteEM").html(nombre);
    $("#idClienteEM").val(idCliente);
    $("#btnEnviarMensaje").show();
}
//modulo mensaje, enviar mensaje
function enviarMensaje()
{
    if($("#formEnviarMensaje").valid()){
        var params = {
            funct: 'enviarMensaje',
            idCliente: $("#idClienteEM").val(),
            titulo: $("#tituloMensaje").val(),
            mensaje: $("#mensajeMensaje").val()
          };
          //ajax para guardar un mensaje
        ajaxData(params, function(data){
          $("#btnEnviarMensaje").hide();
          $('#formEnviarMensaje')[0].reset();
          //Veirifica si se inserto el mensaje
         if(data.idMensaje > 0)
         {
            $("#mensajesuccess").show();
            $("#mensajewarning").hide();
            setTimeout(function(){
              $("#mensajesuccess").hide();
              parent.$.fancybox.close();
              $("#btnBuscarMensajes").click();
            }, 3000);
         }
         else
         {
            $("#mensajewarning").show();
            $("#mensajesuccess").hide();
            setTimeout(function(){
              $("#mensajewarning").hide();
              parent.$.fancybox.close();
            }, 3000);
         }
      });
    }
    else
    {
      console.log("no");
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
//Catalogo documentos clientes: funcion para obtener los requisitos de cierto documento
function obtenerRequisitosDocumento(idDocND, descripcion)
{
    $("#idDocND").val(idDocND);
    $("#descripcionDocumento").html(descripcion);
    var params = {
          funct: 'obtenerRequisitosDocumento',
          idDocND: idDocND
      };
      //ajax para guardar historico y redireccionar
      ajaxData(params, function(data){
        console.log(data);
        $(".datable_bootstrap").html(generaTablaRequisitos(data));
         //inicializar tabla
         $('#tabla_requisitos').DataTable({
              scrollY: true,
              scrollX: true,
              searching: false,
              dom: 'Bfrtip',
              bInfo: true,
              responsive: true,
              "language": {
                  "url": "../js/Spanish.json"
              },
              aaSorting: []
          });
      });
}
//Funcion para generar la tabla de requisitos a partir del array
function generaTablaRequisitos(requisitos)
{
    var tabla = '<table id="tabla_requisitos" class="table table-striped table-bordered table-condensed dataTable no-footer dt-responsive" role="grid" cellspacing="0" width="100%" >'+
                '<thead>'+
                    '<tr>'+
                        '<th># <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>'+
                        '<th>Requisito <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>'+
                        '<th width="80px">Acci&oacute;n <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>'+
                    '</tr>'+
                '</thead>'+
                '<tbody>';
    var cont = 1;
    requisitos.forEach(function(requisito){
      tabla += '<tr>'+
            '<td>'+cont+'</td>'+
            '<td>'+requisito.nombreRequisito+'</td>'+
            '<td>'+
                '<a class="cursorPointer" title="Editar" onclick="editarRequisito('+requisito.idRequisito+',\''+requisito.nombreRequisito+'\')"><img src="../images/icon_editar.png" class="iconoDesactivar" title="Editar" alt="Editar"></a>'+
                '<a class="cursorPointer" title="Eliminar" onclick="eliminarRequisito('+requisito.idRequisito+')"><img src="../images/icon_delete.png" class="iconoDesactivar" title="Eliminar" alt="Eliminar"></a>'+
            '</td>'+
            '</tr>';
      cont++;
    });
    tabla += '</tbody>'+
            '</table>';
    return tabla;
}
//Funcion para guardar o actualizar un requisito de un documento de clientes
function aceptarRequisito()
{
    if($("#formRequisitos").valid()){
      var params = {
          funct: 'aceptarRequisito',
          idDocND: $("#idDocND").val(),
          requisito: $("#descripcionRequisito").val(),
          idRequisito: $("#idRequisito").val()
      };
      //ajax para guardar 
      ajaxData(params, function(data){
        if(data.result > 0)
        {
            $("#requisitosuccess").show();
            $("#requisitowarning").hide();
            setTimeout(function(){
              $("#requisitosuccess").hide();
            }, 3000);
            obtenerRequisitosDocumento($("#idDocND").val(), $("#descripcionDocumento").text());
        }
        else
        {
            $("#requisitowarning").show();
            $("#requisitosuccess").hide();
            setTimeout(function(){
              $("#requisitowarning").hide();
            }, 3000);
        }
       limpiar('requisito');
      });
    }
    else
    {
      console.log("invalido");
    }
}
//
function eliminarRequisito(idRequisito)
{
    var params = {
          funct: 'eliminarRequisito',
          idRequisito: idRequisito
      };
      //ajax para guardar 
      ajaxData(params, function(data){
        if(data.result > 0 )
        {
            $("#requisitosuccess").show();
            $("#requisitowarning").hide();
            setTimeout(function(){
              $("#requisitosuccess").hide();
            }, 3000);
            obtenerRequisitosDocumento($("#idDocND").val(), $("#descripcionDocumento").text());
        }
        else
        {
            $("#requisitowarning").show();
            $("#requisitosuccess").hide();
            setTimeout(function(){
              $("#requisitowarning").hide();
            }, 3000);
        }
       limpiar('requisito');
      });
}
function editarRequisito(idRequisito, descripcion)
{
    $("#requisitoeditando").show();
    $("#descripcionRequisito").val(descripcion);
    $("#idRequisito").val(idRequisito);
}
//Funcion para limpiar formulario, tipo es el tipo de formulario a limpiar
function limpiar(tipo)
{
    if(tipo == 'requisito')
    {
        $("#descripcionRequisito").val("");
        $("#idRequisito").val(0);
        $("#requisitoeditando").hide();
        $("textarea").removeClass("error");
        $("label.error").hide();
    }
    else if(tipo == "cambiopass")
    {
        $('#formCambiarPass')[0].reset();
        $("label.error").hide();
        $("label.valid").hide();
        $("#cambiosuccess").hide();
        $("#cambiowarning").hide();
        $("input").removeClass("error");
        $(".glyphicon-eye-open").hide();
    }
}
//Funcion para inicializar el fancy de subir imagenes prototipo
function inicializaImagenesPrototipo(idPrototipo, comercial){
  $("#idPrototipo").val(idPrototipo);
  $("#nombrePrototipo").html(comercial);
  console.log(comercial);
  var params = {
          funct: 'obtenerImgPrototipo',
          idPrototipo: idPrototipo
      };
      //ajax para guardar 
      ajaxData(params, function(data){
        html = '';
        if(data.res == 'ok')
        {
          html += '<div class="row">';
             data.images.forEach(function(image){
              html += '<div class="col-sm-6 col-md-3"><a class="thumbnail"><img src="../upload/prototipos/'+image.nombreImagen+'" alt="'+image.nombreImagen+'"></a>'+
              '<!--<div class="caption"><img src="../images/icon_delete.png" class="iconoDesactivar" ></div>--></div>';
            });
          html += '</div>';
        }
        $("#imagenesprevia").html(html);
       console.log(data);
      });
}
function cargarImagePrototipo()
{
    if($("#formImgProt").valid({rules: {
        fileupload: {
            required: true,
            accept: "image/x-png,image/gif,image/jpeg"
          }
    }}))
    {
      console.log("valido");
    }
    else
    {
      console.log("invalido");
      return false;
    }
}
function subirArchivosPorAjax(file, url, imgAnteriorEd, nomImg, tipo, funcionRes)
{
  var carpeta = '';
    if($("#nombrecarpeta").length)
    {
      carpeta = $("#nombrecarpeta").val();
    }
    var data = new FormData();
            console.log(nomImg);
            data.append('file', file);
            data.append('tipo',tipo);
            data.append('carpeta', carpeta);
            data.append('nomImg',nomImg);
            data.append('imgAnteriorEd', imgAnteriorEd);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (rponse) {
                    funcionRes();
                    $("#resultadoSubir").val(rponse);
                }
                 });
}
function cambiarPass()
{
    $.validator.setDefaults({
      debug: true,
      success: "valid"
    });
    $.validator.addMethod("notEqual", function(value, element, param) {
     return this.optional(element) || value != $(param).val();
    }, "Este campo debe ser diferente al anterior");
    $( "#formCambiarPass" ).validate({
      rules: {
        passant: {
          minlength: 5
        },
        passnue: {
          notEqual: "#passant",
          minlength: 5
        },
        passconf: {
          equalTo: "#passnue",
          minlength: 5
        }
      }
    });
  if($("#formCambiarPass").valid())
  {
      var params = {
          funct: 'cambiarPass',
          idUsuario: $("#idUsuario").val(),
          passant: $("#passant").val(),
          passnue: $("#passnue").val(),
      };
      //ajax para guardar 
      ajaxData(params, function(data){
          console.log(data);
          if(data.res)
          {
              $("#cambiosuccess").show();
              $("#cambiowarning").hide();
              $("#mensajesuccess").html(data.mensaje);
              $("#mensajewarning").html("");
              setTimeout(function(){
                limpiar('cambiopass');
              }, 5000);
          }
          else
          {
              $("#cambiosuccess").hide();
              $("#cambiowarning").show();
              $("#mensajesuccess").html("");
              $("#mensajewarning").html(data.mensaje);
          }
      });
  }
  else
  {
    console.log("invalido");
  }
}
function muestraEditarPuesto(idUsuario, nombre, idPuesto)
{
    console.log(idPuesto);
    $("#idUsuarioEP").val(idUsuario);
    $("#nombreUsuarioTextEP").html(nombre);
    $('#puestoUsuario option[value="'+idPuesto+'"]').prop("selected", true);
}
function muestraEditarColaborador(idUsuario, nombre){
    console.log(idUsuario);
    $("#idUsuarioEC").val(idUsuario);
    $("#nombreUsuarioTextEC").html(nombre);
//    $("#telefono").val();
}
function actualizarPuestoUsuario()
{
    var params = {
          funct: 'actualizarPuestoUsuario',
          idUsuario: $("#idUsuarioEP").val(),
          idPuesto: $("#puestoUsuario").val(),
      };
      ajaxData(params, function(data){
          console.log(data);
          if(data.res)
          {
              $("#cambiosuccess").show();
              $("#cambiowarning").hide();
              $("#mensajesuccess").html(data.mensaje);
              $("#mensajewarning").html("");
              setTimeout(function(){
                  $("#cambiosuccess").hide();
                  location.reload();
              }, 5000);
          }
          else
          {
              $("#cambiosuccess").hide();
              $("#cambiowarning").show();
              $("#mensajesuccess").html("");
              $("#mensajewarning").html(data.mensaje);
              setTimeout(function(){
                  $("#cambiowarning").hide();
              }, 5000);
          }
      });
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
function subirImagenPerfil(file, url, imgAnteriorEd, nomImg, tipo)
{
    file = $('input#'+file)[0].files[0];
    imgAnteriorEd = $("#imagenActual").val();
    var n=Math.floor(Math.random()*111);
    var k = Math.floor(Math.random()* 10000000);
    var m = n+k;
    var ext = getFileExtension(file.name);
    var nomImg = m+"."+ext;
    console.log(nomImg);
    if(ext == 'jpg' ||  ext == 'jpeg' || ext =='gif' || ext == 'png' || ext == 'tif' || ext == 'tiff' || ext == 'bmp' )
      subirArchivosPorAjax(file, url, imgAnteriorEd, nomImg, tipo, function(){
    setTimeout(function(){
        if ($("#resultadoSubir").val().indexOf('se ha subido correctamente')!=-1) {
            $("#imgperfil").attr("src","../upload/empleados/"+nomImg);
            $("#imagenActual").val(nomImg);
            var params = {
              funct: 'actualizaImagenPerfil',
              idEmpleado: $("#idEmpleado").val(),
              foto: nomImg
            };
             ajaxData(params, function(data){
              console.log(data);
              });
        }
        $("#mensajecarga").html('<p>'+$("#resultadoSubir").val()+'</p>');
    }, 500);
      });
    else
    {
        $("#resultadoSubir").val("El tipo de archivo ."+ext+" no esta permitido");
    }
}
function muestraPermisosDoc(idDocumento, nombre, permisos)
{
    $("#idDocCI").val(idDocumento);
    $("#nombreDocCI").html(nombre);
     var params = {
          funct: 'generaSelectPermisosDocCI',
          idDocumento: idDocumento,
          permisos: permisos,
      };
      ajaxData(params, function(data){
              console.log(data);
              $("#listaPuestos").html(data.select);
              $("#listaPuestos").bootstrapDualListbox({
                  nonSelectedListLabel: 'Non-selected',
                  selectedListLabel: 'Selected',
                  preserveSelectionOnMove: 'moved',
                  moveOnSelect: false
              });
      });
}
function guardarPermisosDocsCI()
{
  //console.log($("#listaPuestos").val().toString());
    $.validator.setDefaults({
      success: "valid"
    });
  if($("#formPermisosCI").valid())
  {
      var params = {
          funct: 'actualizaPermisosDocCI',
          idDocumento: $("#idDocCI").val(),
          permisos: $("#listaPuestos").val().toString(),
      };
      ajaxData(params, function(data){
              console.log(data);
              if(data.res)
              {
                  if(data.obj > 0)
                  {
                      $("#cambiosuccess").show();
                      $("#cambiowarning").hide();
                      $("#mensajesuccess").html("Cambios guardados correctamente");
                  }
              }
              else
              {
                  $("#cambiosuccess").hide();
                  $("#cambiowarning").show();
                  $("#mensajewarning").html("No hay cambios que guardar");
              }
              setTimeout(function(){
                  $("#cambiosuccess").hide();
                  $("#cambiowarning").hide();
                  $("label.valid").hide();
               }, 3000);
              });
  }
  else
  {
    console.log("invalido");
  }
}
function actualizarPermiso(idPermiso)
{
    var valor = 0;
    if( $('#permiso_'+idPermiso).prop('checked') ) {
        valor = 1;
    }
    var params = {
          funct: 'actualizaPermiso',
          idPermiso: idPermiso,
          valor: valor,
          columna: $("#tipoP").val()
      };
    ajaxData(params, function(data){
          console.log(data);
          if(data.success)
          {
              if(data.res > 0){
                  alertify.success("Se ha actualizado correctamente."); 
                  return false;
                 // alert("Se ha actualizado correctamente");
              }
          }
      });
}
//Sirve para mandar parametros y actualizar cualquier campo de la vivienda no solo la renta
function editarRentaCliente(campo, valor, idVivienda)
{
    var params = {
          funct: 'editarVivienda',
          idVivienda: idVivienda,
          valor: valor,
          campo: campo
      };
      //console.log(params);
    ajaxData(params, function(data){
          console.log(data);
          if(data.success)
          {
              if(data.res > 0){
                alertify.success("Se ha actualizado correctamente."); 
                return false;
                //  alert("Se ha actualizado correctamente");
              }
          }
      });
}


function editarDocumento(campo, valor, idDocumento)
{
    var params = {
          funct: 'editarDocumento',
          idVivienda: idDocumento,
          valor: valor,
          campo: campo
      };
      //console.log(params);
    ajaxData(params, function(data){
          console.log(data);
          if(data.success)
          {
              if(data.res > 0)
              {
                  alert("Se ha actualizado correctamente");
              }
          }
      });
}

function actualizarColaboradorUsuario(){
    var params = {
          funct: 'actualizarColaboradorUsuario',
          idUsuario: $("#idUsuarioEC").val(),
          telefono: $("#telefono").val()
      };
      ajaxData(params, function(data){
          console.log(data);
          if(data.success){
              if(data.res > 0){
                alertify.success("Se ha actualizado correctamente."); 
                return false;
                  //alert("Se ha actualizado correctamente");
              }
          }
      });
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























/*INICIO FUNCIONES PARA OBTENER COORDENADAS DE UN MAPA EN UN GRID*/
function obtCoordenasPuntoEnMapa(){  
  // posicion marcador origen
  var lat = marcadorEnMapa.getPosition().lat();
  var lng = marcadorEnMapa.getPosition().lng();
  latLng = lat+','+lng;
  //Este cambia dependiendo de la posición
  idGrid = $("#idGrid").val();
     //console.log(idGrid);
  if(idGrid=="origen"){
    //$("#OrigenesPorProveedorGrid_mt_nr_OrigenesPorProveedorGrid_mt_c6_input").val(latLng);
    //Se reemplazo porque en el servidor de 3cmaquinaria no funciona el grid para actualizar y agregar un origen
    $("#coordenadaText").val(latLng);
  }
  if(idGrid=="destino"){
    $("#DestinosPorClienteGrid_mt_nr_DestinosPorClienteGrid_mt_c6_input").val(latLng);
  }
  if(idGrid=="obras_cat"){
    $("#obrasGrid_mt_nr_obrasGrid_mt_c8_input").val(latLng);
    //$("#obrasGrid_mt_nr_obrasGrid_mt_c4_input").val(latLng);     
  }
  idRowTmpEdit = $("#idRowTmp").val();
  // console.log(idRowTmpEdit);
  $("#"+idRowTmpEdit).val(latLng);
}
//Inicializar mapa desde las vistas (Agregar destino)
var mapDos;
var marcadorEnMapa;
function initMapOnePoint() {
  mapDos = new google.maps.Map(document.getElementById('map'), {
    center: origenLatLong,
    zoom: 14
  });
  //agregar marcador Origen
  puntoEnMapa(origenLatLong);
  //escuchar el evento mouseup de cada uno de los marcadores
  marcadorEnMapa.addListener('mouseup', function() { obtCoordenasPuntoEnMapa(); });
}
//Agrega marcador al mapa
function puntoEnMapa(location) {
  marcadorEnMapa = new google.maps.Marker({
    position: location,
    map: mapDos,
    draggable: true,
    title: 'Mapa'
  });
}
//obtener las coordenadas
function obtCoordenasPuntoEnMapa(){
  // posicion marcador origen
  var lat = marcadorEnMapa.getPosition().lat();
  var lng = marcadorEnMapa.getPosition().lng();
  latLng = lat+','+lng;
  console.log(latLng);
  //Este cambia dependiendo de la posición
  idGrid = $("#idGrid").val();
 console.log(idGrid);
  if(idGrid=="origen"){
    //$("#OrigenesPorProveedorGrid_mt_nr_OrigenesPorProveedorGrid_mt_c6_input").val(latLng);
    //Se reemplazo porque en el servidor de 3cmaquinaria no funciona el grid para actualizar y agregar un origen
    $("#coordenadaText").val(latLng);
  }
  if(idGrid=="desarrollos_cat"){
    $("#desarrollosGrid_mt_r0_desarrollosGrid_mt_c3_input").val(latLng);
  }
  if(idGrid=="notarias_cat"){
    $("#notariasGrid_mt_r0_notariasGrid_mt_c5_input").val(latLng);
  }
  idRowTmpEdit = $("#idRowTmp").val();
  // console.log(idRowTmpEdit);
  $("#"+idRowTmpEdit).val(latLng);
}
function OnBeforeStartInsert()
{
  console.log("before");
    $("#cont_mapa").show();
    $.ajax({
        url: "https://maps.googleapis.com/maps/api/js?key=AIzaSyAIcMHLHaand8rK6YK67fSmm2nXU8boz7A&callback=initMapOnePoint",
        dataType: "script",
        cache: true,
        success: function() {
            //initMapOnePoint();
        }
    });
    return true; // Approve action
}
function OnConfirmInsert(sender,args)
{
  console.log("confirm");
    idGrid = $("#idGrid").val();
    console.log(idGrid);
    if(idGrid=="origen"){
      // direccion = $("#OrigenesPorProveedorGrid_mt_nr_OrigenesPorProveedorGrid_mt_c1_input").val();
      Coordenada = $("#OrigenesPorProveedorGrid_mt_nr_OrigenesPorProveedorGrid_mt_c6_input").val();
    }
    if(idGrid=="destino"){
      // direccion = $("#DestinosPorClienteGrid_mt_nr_DestinosPorClienteGrid_mt_c1_input").val();
      Coordenada = $("#DestinosPorClienteGrid_mt_nr_DestinosPorClienteGrid_mt_c6_input").val();
    }
    if(idGrid=="obras_cat"){        
      Coordenada = $("#obrasGrid_mt_nr_obrasGrid_mt_c8_input").val(); 
    }
    // console.log(Coordenada);
    if(Coordenada!=""){
        $("#cont_mapa").hide();
    }
}
function OnCancelInsert(sender,args)
{
  console.log("cancel");
    $("#cont_mapa").hide();
}
function OnRowStartEdit(sender,args)
{
  console.log("edit");
    idGrid = $("#idGrid").val();
    // console.log(idGrid);
    if(idGrid=="origen"){
      OrigenesPorProveedorGrid.refresh(); //refrescar para cerrar la edicion anterior
      colName = "_OrigenesPorProveedorGrid_mt_c6_input";
    }
    if(idGrid=="desarrollos_cat"){
      desarrollosGrid.refresh(); //refrescar para cerrar la edicion anterior
      colName = "_desarrollosGrid_mt_c3_input";
    }
    if(idGrid=="notarias_cat"){ 
      notariasGrid.refresh(); //refrescar para cerrar la edicion anterior
      colName = "_notariasGrid_mt_c5_input";
    }
     var _row = args["Row"];
     idRow = _row.id;
     idRow = idRow+colName;     
     $("#idRowTmp").val(idRow);
     numberRow = _row.id;
     numberRow = parseInt(numberRow.substr(-1));
     OnBeforeStartInsert();
}
function OnRowConfirmEdit()
{
  console.log("confirm edit");
    $("#cont_mapa").hide();
}
function OnRowCancelEdit(sender,args)
{
  console.log("cancel edit");
    $("#cont_mapa").hide();
}
/*FIN FUNCIONES PARA OBTENER COORDENADAS DE UN MAPA EN UN GRID*/
function addReadonlyFieldGrid(id)
{
     setTimeout(function(){
      console.log("readonly");
      $("#"+id).attr('readonly', true);
      //$("#"+id).val('');
    }, 800);
}
function mostrarImagenActual(idRow)
{
    var nomImg = $("#"+idRow).val();
    if(nomImg != "")
    {
      var url = '../upload/decoradorVirtual/'+$("#nombrecarpeta").val()+'/';
        $('.output').html('<img width="100" src="' + url+nomImg + '" />');
    }
}
/*Inicio funciones para cargar imagenes de catalogos*/
function OnBeforeStartInsertTM()
{        
    console.log("before: ");
    $(".demo-droppable").show();
    $(".output").show();
    if( $("#editandoReg").val() == "" )
    {
         $(".output").html("");
        $("#mensajeCargarImagenes").hide();
    }
    addReadonlyFieldGrid('tipoMueblesGrid_mt_nr_tipoMueblesGrid_mt_c2_input');
    return true; // Approve action
}
function OnConfirmInsertTM(sender,args)
{
  console.log("confirm");
    idGrid = $("#idGrid").val();
    console.log(idGrid);
    if(idGrid=="tipomuebles_cat"){
      // direccion = $("#OrigenesPorProveedorGrid_mt_nr_OrigenesPorProveedorGrid_mt_c1_input").val();
      Coordenada = $("#tipoMueblesGrid_mt_nr_tipoMueblesGrid_mt_c2_input").val();
    }
    if(idGrid=="muebles_cat"){
      // direccion = $("#DestinosPorClienteGrid_mt_nr_DestinosPorClienteGrid_mt_c1_input").val();
      Coordenada = $("#MueblesGrid_mt_nr_MueblesGrid_mt_c4_input").val();
    }
    if(idGrid=="obras_cat"){        
      Coordenada = $("#obrasGrid_mt_nr_obrasGrid_mt_c8_input").val(); 
    }
    // console.log(Coordenada);
    if(Coordenada!=""){
        $(".demo-droppable").hide();
        $(".output").hide();
        $(".output").html("");
        $("#mensajeCargarImagenes").hide();
        $("#editandoReg").val("");
    }
    addReadonlyFieldGrid('tipoMueblesGrid_mt_nr_tipoMueblesGrid_mt_c2_input');
}
function OnCancelInsertTM(sender,args)
{
  console.log("cancel");
    $(".demo-droppable").hide();
    $(".output").hide();
    $("#mensajeCargarImagenes").hide();
    $("#editandoReg").val("");
}
function OnRowStartEditTM(sender,args)
{
  console.log("edit");
    idGrid = $("#idGrid").val();
    // console.log(idGrid);
    if(idGrid=="tipomuebles_cat"){
      tipoMueblesGrid.refresh(); //refrescar para cerrar la edicion anterior
      colName = "_tipoMueblesGrid_mt_c2_input";
    }
    if(idGrid=="muebles_cat"){
      MueblesGrid.refresh(); //refrescar para cerrar la edicion anterior
      colName = "_MueblesGrid_mt_c4_input";
    }
    if(idGrid=="notarias_cat"){
      notariasGrid.refresh(); //refrescar para cerrar la edicion anterior
      colName = "_notariasGrid_mt_c5_input";
    }
     var _row = args["Row"];
     idRow = _row.id;
     idRow = idRow+colName;
     $("#idRowTmp").val(idRow);
     addReadonlyFieldGrid(idRow);
     mostrarImagenActual(idRow);
     if($("#"+idRow).val() != "")
     {
        $("#mensajeCargarImagenes").show();
     }
     $("#editandoReg").val("si");
     numberRow = _row.id;
     numberRow = parseInt(numberRow.substr(-1));
     OnBeforeStartInsertTM();
}
function OnRowConfirmEditTM()
{
  console.log("confirm edit");
     $(".demo-droppable").hide();
    $(".output").hide();
    $(".output").html("");
    $("#mensajeCargarImagenes").hide();
    addReadonlyFieldGrid( $("#idRowTmp").val());
    $("#editandoReg").val("");
}
function OnRowCancelEditTM(sender,args)
{
  console.log("cancel edit");
     $(".demo-droppable").hide();
    $(".output").hide();
    $(".output").html("");
    $("#mensajeCargarImagenes").hide();
    $("#editandoReg").val("");
}
/*Fin funciones para cargar imagenes de catalogos*/
/*Obtener extension de un archivo*/
function getFileExtension(filename) {
  return (/[.]/.exec(filename)) ? /[^.]+$/.exec(filename)[0] : undefined;
}
/*Obtener parametro dada una url*/
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}