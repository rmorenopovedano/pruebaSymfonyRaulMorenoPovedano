/**
 * Created by sempe on 27/01/2017.
 */
$( document ).ready(function() {
    var emailOK=true;
    var nombreOK=true;
    var telefonoOK=true;
    var nombre=$('#form_nombre');
    nombre.on('blur', function(){
        if(nombre.val().trim()==""){
            $(this).parent().addClass('has-error');
            nombreOK=false;
        }else{
            $(this).parent().removeClass('has-error');
            nombreOK=true;
        }
    });
    $('#form_email').blur(function() {
        // Expresion regular para validar el correo
        var email=$(this).val();
        var regexEmail = /^[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}$/;
        if (email.trim().match(regexEmail)) {
            $(this).parent().removeClass('has-error');
            emailOK=true;
        } else {
            emailOK=false;
            $(this).parent().addClass('has-error');
        }
    });
    $('#form_telefono').blur(function(){
        var telefono=$(this).val();
        var regexTelefono= /^[\d]+$/;
        if(!telefono.match(regexTelefono)){
            telefonoOK=false;
            $(this).parent().addClass('has-error');
        }else{
            telefonoOK=true;
            $(this).parent().removeClass('has-error');
        }
    });
    $('#form_save').click(function(){
        if(telefonoOK && emailOK && nombreOK){
            $('form[name="form"]').submit;
        }else{
            $('#info').show();
            return false;
        }
    });

});