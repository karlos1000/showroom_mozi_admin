$(function(){
  /*
   * For the sake keeping the code clean and the examples simple this file
   * contains only the plugin configuration & callbacks.
   * 
   * UI functions ui_* can be located in: demo-ui.js
   */
  $('#drag-and-drop-zone').dmUploader({ //
    // url: 'backend/upload.php',
    url: '../js/uploader-master/backend/upload.php',
    //maxFileSize: 3000000, // 3 Megs 
	  maxFileSize: 90000000, // 90 Megs 
    onDragEnter: function(){
      // Happens when dragging something over the DnD area
      this.addClass('active');
    },
    onDragLeave: function(){
      // Happens when dragging something OUT of the DnD area
      this.removeClass('active');
    },
    onInit: function(){
      // Plugin is ready to use
      //ui_add_log('Penguin initialized :)', 'info');
    },
    onComplete: function(){
      // All files in the queue are processed (success or error)
      ui_add_log('Todos los archivos han terminado de subirse');
    },
    onNewFile: function(id, file){
      // When a new file is added using the file selector or the DnD area
	   if(file.type=="video/mp4"){
		   //ui_add_log('New file added #' + id);
		   ui_multi_add_file(id, file);
	   }else{
       console.log("Archivo no soportado");
		   ui_add_log('Archivo #' + file.name+ ' no soportado.', 'danger');       
		   return false;
	   }	  	
	  //console.log(file);
	  //console.log(file.size);
	  //console.log(file.type);
    },
    onBeforeUpload: function(id){
      // about tho start uploading a file
      //ui_add_log('Starting the upload of #' + id);
      ui_multi_update_file_status(id, 'uploading', 'Uploading...');
      ui_multi_update_file_progress(id, 0, '', true);	  
    },
    onUploadCanceled: function(id) {
      // Happens when a file is directly canceled by the user.
      ui_multi_update_file_status(id, 'warning', 'Cancelado por el usuario');
      ui_multi_update_file_progress(id, 0, 'warning', false);
    },
    onUploadProgress: function(id, percent){
      // Updating file progress
      ui_multi_update_file_progress(id, percent);
    },
    onUploadSuccess: function(id, data){
	    // console.log(data);	
      // A file was successfully uploaded
      //ui_add_log('Server Response for file #' + id + ': ' + JSON.stringify(data));
      ui_add_log('Subida de archivo #' + id + ' COMPLETADO', 'success');
      ui_multi_update_file_status(id, 'success', 'Carga completa');
      ui_multi_update_file_progress(id, 100, 'success', false);

      if(data.status=="ok"){
        var path = data.path;
        var pathArr = path.split("upload/");
        path = "../upload/"+pathArr[1];
        // console.log(data.path);
        // console.log(pathArr);
        // console.log(path);

        var gModeloId = $("#idGM").val();
        var url = $.base64.encode(path);
        var params = {funct: 'insertarInfoVideo', url:url, gModeloId:gModeloId};
        // console.log(params);
        // return false;
        ajaxData(params, function(data){
          // console.log(data);
          if(data.success==true){
            alertify.success("Video subido correctamente");
            videosModeloGrid.refresh(); 
            videosModeloGrid.commit();
          }else{
            alertify.error("El video no fue posible subirlo");
          }
        });      
      }
    },
    onUploadError: function(id, xhr, status, message){
      ui_multi_update_file_status(id, 'danger', message);
      ui_multi_update_file_progress(id, 0, 'danger', false);  
    },
    onFallbackMode: function(){
      // When the browser doesn't support this plugin :(
      ui_add_log('El complemento no se puede usar, ejecutando devolución de llamada Fallback', 'danger');
    },
    onFileSizeError: function(file){
      ui_add_log('Archivo \'' + file.name + '\' no se puede agrega: límite de exceso de tamaño', 'danger');
    }
  });
});