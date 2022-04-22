<?php

function registratr_config_page(){
    ?>
   <div class="wrap">
   <h1>RegistraTr Plugin</h1>
    <table class="form-table">
    <tbody><tr>
        <th scope="row"><label for="recreate">Recreate codes in all users</label></th>
        <td><input name="recreate" type="button" id="recreate" value="Recreate code" class="regular-text"></td>
        </tr>
        <tr>
        <th scope="row"><label for="sendmailcheck">Send email</label></th>
        <td><input name="sendmailcheck" type="checkbox" id="sendmailcheck" class="regular-text"></td>
        </tr>
        <tr>
        <th scope="row"><label for="emailforreg">email to registered user</label></th>
        <td><textarea rows = "5" cols = "50" name="emailforreg" type="textarea" id="emailforreg" value="Write here your message" class="regular-text">
        </textarea>
        </td>
        </tr>
        <tr>
        <th scope="row"><label for="emailfornew">email to new user</label></th>
        <td><textarea rows = "5" cols = "50" name="emailfornew" type="textarea" id="emailfornew" value="Write here your message" class="regular-text">
        </textarea>
        </td>       
        </tr>
        
    </tbody>
    </table>
    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Guardar cambios"></p>
        <form action="" method="post">
        <p><button type="submit" value="Recrear"/></p>  
    </div>  
    </html>
<?php
}

?>