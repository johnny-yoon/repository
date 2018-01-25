<?php
    $user_id=get_current_user_id();
    $xcrud = Xcrud::get_instance();
    $xcrud->table('wx_deposit');
    $xcrud->where('user_id=',$user_id);
    echo $xcrud->render();
?>