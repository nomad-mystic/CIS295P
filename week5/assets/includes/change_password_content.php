<?php
/**
 * Created by PhpStorm.
 * User: Nomad_Mystic
 * Date: 5/3/2016
 * Time: 4:56 PM
 */

require_once('LoadableContent.php');
require_once('User.php');

$change_password_username_key = User::CHANGE_PASSWORD_USERNAME_KEY;
$change_password_code_key = User::CHANGE_PASSWORD_CODE_KEY;
$change_password_key = User::CHANGE_PASSWORD_KEY;
$change_password_confirmation_key = User::CHANGE_PASSWORD_CONFIRMATION_KEY;
$status_error = User::STATUS_ERROR;

$js = <<<JS
function changePassword(username, code) {
    // clear inputs 
    $('#change_password_dialog input').val('');
    // clear errors 
    $('.change_password_error_message').html('');
    
    // activate register dialog modal 
    $('#change_password_dialog').dialog({
        width: 600,
        model: true,
        buttons: {
            'Change Password': function() 
            {
                var cookies = document.cookie.split('; ');
                console.log(cookies);
                var sess_id = '';
                for (var i=0; i < cookies.length; i++) { 
                    if( cookies[i].indexOf('PHPSESSID=') == 0) { 
                        sess_id = cookies[i].substr(cookies[i].indexOf('=') + 1); 
                        console.log(sess_id + ' Session ID');
                    } 
                } // end cookies 
                
                $.post(
                // inside location window object 
                'https://' 
                // + location.host 
                + 'localhost'
                + location.pathname.substr(0, location.pathname.lastIndexOf('/')) 
                + '/assets/actions/do_change_password.php',
                $('#change_password_dialog input').serialize() + '&sess_id=' + sess_id + '&username=' + username + '&code=' + code,
                function(data) {
                console.log(data);
                    if (data.status === "{$status_error}") {
                        $('.change_password_error_message').html(data.message);
                    } else {
                        $('#change_password_dialog').dialog('close');
                        updateNavbar();
                    }
                });
            },
            'Cancel': function() 
            {
                $('#change_password_dialog').dialog('close');
            }
        }
    }); // end jQuery dialog
}
JS;

$html = <<<HTML
<div title="Please selected your new password." id="change_password_dialog">
    <div class="change_password_error_message"></div>
    <p id="change_password_header">Please Enter Your Name, Email, and Password and confirm your new password:</p>
    <fieldset>
            <!--password confirmation-->
            <label for="{$change_password_key}">Password</label>
        <input type="password" name="{$change_password_key}" 
            id="{$change_password_key}" value="">
            
            <!--password confirmation-->
            <label for="{$change_password_confirmation_key}">Password Confirmation</label>
        <input type="password" name="{$change_password_confirmation_key}" 
            id="{$change_password_confirmation_key}" value="">
    </fieldset>
</div>

HTML;

$css = <<<CSS
fieldset {
     padding: 20px;
}
fieldset input {
     display: block;
     margin-bottom: 12px;
     width: 30em;
}
fieldset label {
     display: block;
}
#change_password_dialog { 
    display: none;
}
.ui-dialog-titlebar-close {
     display: none;
}
CSS;
$object = new LoadableContent($js, $html, $css);
$object->load();
