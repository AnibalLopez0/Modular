function validar(){
    var username = $('#username').val();
    var correo = $('#correo').val();
    var pass = $('#pass').val();

    return username && correo && pass;
}

$(document).ready(function(){

    // VALIDAR CORREO
    $('#correo').blur(function(){

        $.ajax({
            type:'POST',
            url:'../Funciones/verificarCorreo.php', // 🔥 corregido (F mayúscula)
            data:{correo:$(this).val()},
            dataType:'json',

            success:function(respuesta){

                if(respuesta.error){

                    $('#ce').html(respuesta.message);
                    $('#correo').val('');

                    setTimeout(function(){
                        $('#ce').empty();
                    },4000);
                }
            }
        });
    });

    // SUBMIT FORM
    $('#alta-form').submit(function(e){

        e.preventDefault();

        if(validar()){

            $('#campos').html("");

            // DEBUG 🔍
            console.log($(this).serialize());

            $.ajax({
                type:'POST',
                url:'../Funciones/insertarMedico.php', // 🔥 corregido
                data:$(this).serialize(),

                success:function(respuesta){

                    console.log("RESPUESTA:", respuesta);

                    if(respuesta == 1){
                        alert("Médico registrado");
                        window.location.href="Central.php";
                    }else{
                        alert(respuesta); //  ahora muestra error real
                    }
                }
            });

        }else{

            $('#campos').html("Faltan campos por llenar");

            setTimeout(function(){
                $('#campos').empty();
            },3000);
        }

    });

});