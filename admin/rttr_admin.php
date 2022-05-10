<?php
//version final



function theme_add_user_code_column( $columns ) {
    $columns['_dni'] = __('DNI', 'theme');
    $columns['_codigo_para_invitar'] = __( 'Código', 'theme' );
    $columns['_activado'] = __( 'Estado de activación', 'theme' );
    $columns['_activar'] = __( 'Activar', 'theme' );

    return $columns;
  } // end theme_add_user_code_column
add_filter( 'manage_users_columns', 'theme_add_user_code_column' );

function theme_show_user_code_data( $value, $column_name, $user_id ) {
  if( '_dni' == $column_name ) {
    return get_user_meta( $user_id, '_rttr_user_document_identification', true );
  }
  if( '_codigo_para_invitar' == $column_name ) {
      return get_user_meta( $user_id, '_codigo_para_invitar', true );
  } // end if
  if( '_activado' == $column_name ) {
    if(get_user_meta( $user_id, '_activado', true )=='1')
    return 'Activado';
    else return 'Desactivado';
  }
  if( '_activar' == $column_name ) {
    if(get_user_meta( $user_id, '_activado', true )=='0')
    return rttr_activate_button($user_id);
  else
    return rttr_disabled_button();  
  }

  } // end theme_show_user_code_data
  add_action( 'manage_users_custom_column', 'theme_show_user_code_data', 10, 3 );
  
  
  function rttr_activate_user($user_id){
  update_user_meta( $user_id, '_activado', "1");
  
}
 function rttr_activate_button($user_id){
    return $html ="<button class='button button-primary activate-user-button' value='". $user_id ."' 'type='button' ondblclick = 'myFunction();'>Activar</button>
    <script>
    jQuery(document).ready(function($) {
    
      $('.activate-user-button').click(function(){

        // This does the ajax request (The Call).

      $.ajax({
          url: ajaxurl, // Since WP 2.8 ajaxurl is always defined and points to admin-ajax.php
          data: {
              'action':'rttr_activate_request', // This is our PHP function below
              'user' : $('.activate-user-button').val() // This is the variable we are sending via AJAX
          },
          success:function(data) {
      // This outputs the result of the ajax request (The Callback)
              window.alert(data);
          },  
          error: function(errorThrown){
              window.alert(errorThrown);
          }
      });   
  });
  });
    </script>";
    }

 function rttr_disabled_button(){
  return  $html = '<button class="button button-primary" disabled="true" type="button" role="button">Activar</button>
  ';
 }


 function rttr_activate_request() {

  // The $_REQUEST contains all the data sent via AJAX from the Javascript call
  if ( isset($_REQUEST) ) {

      $user = $_REQUEST['user'];
      // Now let's return the result to the Javascript function (The Callback) 
      rttr_activate_user($user);
      echo 'El usuario ha sido activado satisfactoriamente' ;        
  }

  // Always die in functions echoing AJAX content
 die();
}

// This bit is a special action hook that works with the WordPress AJAX functionality. 
add_action( 'wp_ajax_rttr_activate_request', 'rttr_activate_request' ); 


function registratr_config_page($page){
  $key = '_rttr_correo_para_invitados';
  $key1 = '_rttr_correo_para_confirmados';
  $key2 = '_rttr_correo_para_anfitriones';
  $key3 = '_rttr_pagina_de_lista_de_pendientes';
  $key4 = '_rttr_pagina_de_no_confirmado';
  $key6 = '_rttr_correo_noresponder';
  $emailtxt1 = get_option($key2);
  $emailtxt1 = utf8_decode($emailtxt1);
  $emailtxt3 = get_option($key1);  
  $emailtxt2 = get_option($key); 
  $page1val = get_option($key3);
  $page2val = get_option($key4);
  $correo = get_option($key6);
    ?>
    <meta charset="UTF-8">
    <script src="https://unpkg.com/boxicons@2.1.2/dist/boxicons.js"></script>

    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

    <style>
      @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;700&display=swap');
      body {

      }

      .fa {
        padding: 10px;
      }

      a:focus {
        box-shadow: none!important;
        outline: 0px solid transparent!important;
      }
      #infotext,
      #infotext2,
      #infotext3 {
        display: none;
      }
      
      .info:hover ~ #infotext {
        display: block;
      }
      .info2:hover ~ #infotext2 {
        display: block;
      }
      .info3:hover ~ #infotext3 {
        display: block;
      }

      p {font-family: 'Nunito Sans', sans-serif;color:black;}

      .wrap.ic_admin {
        background:white;
        padding:2em 5em;
      }

      .ic_admin h1 {
        font-family: 'Nunito Sans', sans-serif;
        font-weight: 300;
        font-size:50px;
      }

      .nav-tabs {
        float:left;
        width:100%;
        margin:0;
        margin-top: 3em;
        list-style-type: none;
      }

      .nav-tabs-inner {
        float:left;
        width:100%;
        margin:0;
        padding:0;
        list-style-type: none;
      }

      .nav-tabs > li,
      .nav-tabs-inner > li {
        float:left;
      }

      .nav-tabs > li > a {
        margin-right:5px;
        background-color:#EDEDED;
        padding:.5em 3em;
        text-decoration: none;
        color:black;
        font-family: 'Nunito Sans', sans-serif;
      }

      .nav-tabs-inner > li > a {
        margin-right:5px;
        padding:.5em 0em;
        text-decoration: none;
        color:black;
        font-family: 'Nunito Sans', sans-serif;
        opacity:.3;
      }

      .nav-tabs-inner > li{
        padding:.5em 1em;
      }
      .nav-tabs-inner > li:nth-child(3){
        background-color: #EDEDED;
      }

      .nav-tabs > li > a:hover,
      .nav-tabs > li > a:focus {
        background-color:#DEDEDE;
        transition: all .2s;
      }

      .nav-tabs-inner > li > a:hover,
      .nav-tabs-inner > li > a:focus {
        opacity:1;
        transition: all .2s;
      }
      .nav-tabs > li.active > a {
        background-color:black;
        color:white;
      }

      .nav-tabs-inner > li.active > a {
        opacity:1;
      }

      .tab-content > .tab-pane {
        display: none;
        float:left;
        width:100%;
        padding:2em 2em;
        margin-top:5px;
      }

      .tab-content > .tab-pane.active {
        display: block;
        background-color:#F8F8F8;
      }

      .tab-inner-content > .tab-inner-pane {
        display: none;
        float:left;
        width:90%;
        padding:0em 0em 0em 1em;
        margin-top:5px;
      }

      .tab-inner-content > .tab-inner-pane.active {
        display: block;
        background-color:#F8F8F8;
      }

      textarea {
        width: 100%!important;;
        border-radius: 0px;
        border-color:#D8D8D8;
        padding:2em;
        font-family: 'Nunito Sans', sans-serif;
      }

      .ad-form-icon {
        float:left;
        display: table;
        height: 80px;
        width:40px;
        text-align: center;
        background-color:#F5E6D1;
      }

      .ad-form-icon i {
        opacity: 0.5;
        display: table-cell; 
        vertical-align:middle;
      }

      .ad-form-text {
        background-color:white;
        display: table;
        height: 80px;
        width:90%;
        padding: 0 2em;
      }

      .ad-form-text p {
        display: table-cell; 
        vertical-align:middle;
      }

      .ic-redirec-block {
        display: block;
        margin-bottom:1em;
      }

      .ic-redirec-block label {
        color:black;
        font-family: 'Nunito Sans', sans-serif;
        font-size:15px;
        font-weight: 600;
        margin-bottom:.5em;
      }

      .ic-redirec-block input {
        font-family: 'Nunito Sans', sans-serif;
        margin-top:.5em;
        border-radius: 0px;
        border-color:#D8D8D8;
        padding:0.3em 1em;
      }
      .button.button-primary.ic-btn {
        background-color:white;
        color:black;
        border-radius:0px;
        border:solid 1px black;
        padding: .5em 2em;
        margin-top:1.5em;
        transition:all .2s;
        font-family: 'Nunito Sans', sans-serif;
      }

      .button.button-primary.ic-btn:hover {
        background-color:rgba(0,0,0,.05);
        
      }

    </style>

   <div class="wrap ic_admin">
   <h1><b>Invitation</b> code</h1><img width="120px" src="<?php echo plugin_dir_url (__FILE__) . '../includes/images/bibiailogo.svg' ?>" />
   <form action="<?php $_SERVER['REQUEST_URI'] ?>" method="post" id="registro">

  <!-- tabs navigator -->

    <script>
      window.addEventListener("load", function() {

        var tabs = document.querySelectorAll("ul.nav-tabs > li");

        for (i = 0; i < tabs.length; i++) {
          tabs[i].addEventListener("click", switchTab);
        }
        
        function switchTab(event) {

          event.preventDefault();
          
          document.querySelector("ul.nav-tabs li.active").classList.remove("active");
          document.querySelector(".tab-pane.active").classList.remove("active");

          var clickedTab = event.currentTarget;
          var anchor = event.target; 
          var activePaneID = anchor.getAttribute("href");

          clickedTab.classList.add("active");
          document.querySelector(activePaneID).classList.add("active");
        }

      });

      window.addEventListener("load", function() {

        var tabs = document.querySelectorAll("ul.nav-tabs-inner > li");

        for (i = 0; i < tabs.length; i++) {
          tabs[i].addEventListener("click", switchTab);
        }

        function switchTab(event) {

          event.preventDefault();
          
          document.querySelector("ul.nav-tabs-inner li.active").classList.remove("active");
          document.querySelector(".tab-inner-pane.active").classList.remove("active");

          var clickedTab = event.currentTarget;
          var anchor = event.target; 
          var activePaneID = anchor.getAttribute("href");

          clickedTab.classList.add("active");
          document.querySelector(activePaneID).classList.add("active");
        }

        });


    </script>

    <ul class="nav-tabs">
      <li class="active"><a href="#tab-avisos">Avisos</a></li>
      <li><a href="#tab-redirecciones">Redirecciones</a></li>
      <li><a href="#tab-ajustes">Ajustes</a></li>
    </ul>

    <div class="tab-content">
      <div id="tab-avisos" class="tab-pane active">
        <!--"avisos" tab-->

        <ul class="nav-tabs-inner">
          <li class="active"><a href="#tab-anfitrion">Anfitrión</a></li>
          <li><a href="#tab-espera">Invitado en espera</a></li>
          <li><a href="#tab-aceptado">Invitado aceptado</a></li>
        </ul>
        <div class="tab-inner-content">
          <div id="tab-anfitrion" class="tab-inner-pane active">
            <div class="ad-form">
            <div class="ad-form-icon"><i class='bx bx-info-circle info2'></i></div>
            <div class="ad-form-text"><p>Este correo le llegará al anfitrión que ha compartido su código de invitación 
              cuando un usuario se registra con dicho código. En este caso, es Necesario que 
              el anfitrión apruebe la solicitud con el enlace de “Lista de espera” previamente creado.</p></div>
            </div><br>
            <label style="display:none;" for="email1">email para usuarios registrados</label>
            <textarea id="message" placeholder="Email para anfitrion" rows = "10" cols = "50" type="textarea" form="registro" name="email1" class="regular-text"><?php echo $emailtxt1 ?></textarea>
            <button id="btn">Append text</button>
          </div>

          <div id="tab-espera" class="tab-inner-pane">
          <div class="ad-form">
            <div class="ad-form-icon"><i class='bx bx-info-circle info2'></i></div>
            <div class="ad-form-text"><p>El usuario que ha procedido con el registro recibirá este correo.</p></div>
            </div><br>
          <label style="display:none;" for="email2">email para nuevos usuarios</label>
          <textarea placeholder="Email para invitado" rows = "10" cols = "50" type="textarea" name="email2"  class="regular-text"><?php echo $emailtxt2 ?></textarea>
          </div>

          <div id="tab-aceptado" class="tab-inner-pane">
          <div class="ad-form">
            <div class="ad-form-icon"><i class='bx bx-info-circle info2'></i></div>
            <div class="ad-form-text"><p>Notifica al usuario registrado de que su cuenta ha sido aprobada por el
              anfitrión que le ha invitado. </p></div>
            </div><br>
          <label style="display:none;" for="email3">email para usuarios confirmados</label>
          <textarea placeholder="Email de aceptacion" rows = "10" cols = "50" type="textarea" name="email3"  class="regular-text"><?php echo $emailtxt3 ?></textarea>
          </div>
        </div>
        <!--end of "avisos" tab-->
      </div>

      <div id="tab-redirecciones" class="tab-pane">
        <div class="ic-redirec-block">
        <label for="page1">Página de lista de espera</label><br>
        <input type="text" placeholder="url de lista de usuarios pendientes" name="page1"  class="regular-text" value="<?php echo $page1val ?>">
        </div>

        <div class="ic-redirec-block">
        <label for="page2">Redirección de usuarios no aceptados</label><br>
        <input type="text" placeholder="Redirecion a usuarios pendientes" name="page2"  class="regular-text" value="<?php echo $page2val ?>">
        </div>
      </div>

      <div id="tab-ajustes" class="tab-pane">
      <div class="ic-redirec-block">
        <label for="correo">Correo emisor</label><br>
        <input type="text" placeholder="Correo de envios de notificaciones" name="correo"  class="regular-text" value="<?php echo $correo ?>">
      </div></div>
    </div>

  <!-- end of tabs navigator -->
    <!--table class="form-table">
    <tbody>
        <tr>
        <th scope="row"><label for="email1">email para usuarios registrados</label></th>
        <td><textarea placeholder="Email para anfitrion" rows = "5" cols = "50" type="textarea" form="registro" name="email1" class="regular-text"><?php echo $emailtxt1 ?></textarea>
        <i class='bx bx-info-circle info'></i>
          <div id="infotext">{nombre sitio} = nombre de la página
           <br>{enlace sitio} = enlace de la página
           <br>{lista usuarios} = enlace a la lista de usuarios
          <br>{nombre usuario} = nombre del usuario al que se dirige el mail
          <br>{nombre invitado} = nombre del usuario invitado</div>
      </td>
        </tr>
        <tr>
        <th scope="row"><label for="email2">email para nuevos usuarios</label></th>
        <td><textarea placeholder="Email para invitado" rows = "5" cols = "50" type="textarea" name="email2"  class="regular-text"><?php echo $emailtxt2 ?></textarea>
        <i class='bx bx-info-circle info2'></i>
        <div id="infotext2">{nombre sitio} = nombre de la página
           <br>{enlace sitio} = enlace de la página
          <br>{nombre usuario} = nombre del usuario al que se dirige el mail
      </td>       
        </tr>
        <tr>
        <th scope="row"><label for="email3">email para usuarios confirmados</label></th>
        <td><textarea placeholder="Email de aceptacion" rows = "5" cols = "50" type="textarea" name="email3"  class="regular-text"><?php echo $emailtxt3 ?></textarea>
        <i class='bx bx-info-circle info3'></i>
        <div id="infotext3">{nombre sitio} = nombre de la página
           <br>{enlace sitio} = enlace de la página
          <br>{nombre usuario} = nombre del usuario al que se dirige el mail
      </td>       
        </tr>
        <tr>
        <th scope="row"><label for="page1">Pagina de usuarios pendientes</label></th>
        <td><input type="text" placeholder="url de lista de usuarios pendientes" name="page1"  class="regular-text" value="<?php echo $page1val ?>">
         </td>       
        </tr>
        <tr>
        <th scope="row"><label for="page2">Pagina de redireccion a no confirmados</label></th>
        <td><input type="text" placeholder="Redirecion a usuarios pendientes" name="page2"  class="regular-text" value="<?php echo $page2val ?>">
         </td>       
        </tr>
        <tr>
        <th scope="row"><label for="correo">direccion Correo para envio de emails</label></th>
        <td><input type="text" placeholder="Correo de envios de notificaciones" name="correo"  class="regular-text" value="<?php echo $correo ?>">
         </td>       
        </tr>
        
    </tbody>
    </table-->
    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary ic-btn" value="Guardar cambios"></p>
    
    </form></div>  
    </html>
<?php
}

function save_options_from_setup($opt2, $opt, $opt3, $pg1, $pg2, $correo ){

  $key = '_rttr_correo_para_invitados';
  $key1 = '_rttr_correo_para_confirmados';
  $key2 = '_rttr_correo_para_anfitriones';
  $key3 = '_rttr_pagina_de_lista_de_pendientes';
  $key4 = '_rttr_pagina_de_no_confirmado';
  $key6 = '_rttr_correo_noresponder';

  if(get_option($key)){

    $user_update = update_option($key, $opt);
  }
  if(get_option($key)){
    $user_update = update_option($key1, $opt3);
  }
  if(get_option($key2)){

      $user_update = update_option($key2, $opt2);
  }
  if(get_option($key3)){
  $user_update = update_option($key3, $pg1);
  }
  if(get_option($key4)){
  $user_update = update_option($key4, $pg2);
  }
  if(get_option($key5)){
    $user_update = update_option($key6, $correo);
    }

}
function registratr_config_form() {
  if ( isset($_POST['submit'] ) ) {
   
      // sanitize user form input
      global $mail1, $mail2, $mail3, $page1, $page2, $correo;
      $mail1   =   sanitize_html($_POST['email1']);
      
      $mail2   =   sanitize_html($_POST['email2']);
      $mail3   =   sanitize_html($_POST['email3']);
      $page1  =   sanitize_text_field($_POST['page1']);
      $page2   =   sanitize_text_field($_POST['page2']);
      $correo = sanitize_email($_POST['correo']);

 
      save_options_from_setup(
        $mail1,
        $mail2,
        $mail3,
        $page1,
        $page2,
        $correo
      );
  }
  registratr_config_page('');
  }
function sanitize_html($str){
  $symbols = array(
    '\"',
    "\'"
  );
  $final = array (
    '"',
    "'"
  );
  $output =str_replace($symbols, $final, $str);
  return $output;
}
?>