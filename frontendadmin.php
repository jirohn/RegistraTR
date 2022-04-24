<?php
function theme_add_user_code_column( $columns ) {
    $columns['_codigo_para_invitar'] = __( 'Código de invitación', 'theme' );
    return $columns;
  } // end theme_add_user_code_column
  add_filter( 'manage_users_columns', 'theme_add_user_code_column' );

function theme_show_user_code_data( $value, $column_name, $user_id ) {

  if( '_codigo_para_invitar' == $column_name ) {
      return get_user_meta( $user_id, '_codigo_para_invitar', true );
  } // end if
  
  } // end theme_show_user_code_data
  add_action( 'manage_users_custom_column', 'theme_show_user_code_data', 10, 3 );
function registratr_config_page(){
    ?>
   <div class="wrap">
   <h1>RegistraTr Plugin</h1>
    <table class="form-table">
    <tbody><tr> 
        <th scope="row"><label for="recreate">Recrear códigos</label></th>
        <td><input name="recreate" type="button" id="recreate" value="Recreate code" class="regular-text"></td>
        </tr>
        <tr>
        <th scope="row"><label for="sendmailcheck">Enviar confirmación?</label></th>
        <td><input name="sendmailcheck" type="checkbox" id="sendmailcheck" class="regular-text"></td>
        </tr>
        <tr>
        <th scope="row"><label for="emailforreg">email para usuario registrado</label></th>
        <td><textarea rows = "5" cols = "50" name="emailforreg" type="textarea" id="emailforreg" value="Write here your message" class="regular-text">
        </textarea>
        </td>
        </tr>
        <tr>
        <th scope="row"><label for="emailfornew">email para nuevo usuario</label></th>
        <td><textarea rows = "5" cols = "50" name="emailfornew" type="textarea" id="emailfornew" value="Write here your message" class="regular-text">
        </textarea>
        </td>       
        </tr>
        
    </tbody>
    </table>
    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Guardar cambios"></p>
    </div>  
    </html>
<?php
}

?>