<?php
/**
 *
 */
class UserController
{
    static public function UploadAvatar()
    {
        $user = unserialize($_SESSION['user']);

        $user = User::getById($user->id);
        
        $upload = AppUtil::uploadImage($_FILES['user_avatar'], UPLOADS_DIR, 'user_'.$user->id.'_avatar');

        $_SESSION['avatar'] = $user->avatar = $upload['filepath'];

        $user->update();

        return json_encode($upload);
    }

}
