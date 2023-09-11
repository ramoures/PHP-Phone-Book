<?php
if (!version_compare(PHP_VERSION, '8.0.0', '>=')) { 
    print "Please update your PHP version to ^8.0.0. your PHP version:".PHP_VERSION;
    die;
}
define('DS',DIRECTORY_SEPARATOR);
define('ROOT_PATH',dirname(__DIR__).DS);
require_once ROOT_PATH.'config.php';
try {
    $projectUrl = str_ends_with(PROJECT_URL,"/")?PROJECT_URL:PROJECT_URL."/";
    $setupLink = $projectUrl."setup/";
    $actualLink = strtok((empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",'?');
    //Check 'PROJECT_URL':
    if($setupLink !== $actualLink)
        throw new Exception("Please set a valid 'PROJECT_URL' on config.php.");
    else{
        require_once ROOT_PATH.'controller/Errors.php';
        require_once ROOT_PATH.'core/Utils.php';
        $u = Utils::getInstance();
        date_default_timezone_set('UTC');
        $dbConnected = false;
        $success1 = false;
        $success2 = false;
        $success3 = false;
        $step = (int)$_GET['step'];
        $step = $step<=0?1:$step;
        $step = $step>2?2:$step;
        $PASSWORD_PATTERN = PASSWORD_PATTERN;
        $forRegex = "/".$PASSWORD_PATTERN."/";
        $tablePrefix = TABLE_PREFIX;

        function encrypt($str){
            try {
                //See also: core/Utils.php
                $result = SECRET_KEY.$str."67H8fgp)@sd8u*ac".$str.SECRET_KEY."546DFsG%^dfA";
                return hash('sha256',$result);
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
        try {
            $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
            $pdo = new PDO($dsn, DB_USER, DB_PASWORD);

            if($pdo){
                $pdo->exec("set names utf8mb4");
                $dbConnected = true;
                $sql1 = "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."admins` (
                    `id` int(10) UNSIGNED NOT NULL,
                    `username` varchar(100) NOT NULL,
                    `password` varchar(64) NOT NULL,
                    `avatar_id` int(11) DEFAULT NULL,
                    `last_signed_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
                ALTER TABLE `".TABLE_PREFIX."admins`
                    ADD PRIMARY KEY (`id`),
                    ADD UNIQUE KEY `id` (`id`),
                    ADD UNIQUE KEY `unique_usersname` (`username`) USING BTREE;
                ALTER TABLE `".TABLE_PREFIX."admins`
                    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;";
                $stmt1 = $pdo->prepare($sql1)->execute();
                if($stmt1)
                    $success1 = true;
                $sql2 = "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."phone_numbers` (
                    `id` int(11) UNSIGNED NOT NULL,
                    `nickname` varchar(100) NOT NULL,
                    `full_name` varchar(255) DEFAULT NULL,
                    `phone_numbers` text NOT NULL,
                    `address` varchar(255) DEFAULT NULL,
                    `image_id` int(11) DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
                ALTER TABLE `".TABLE_PREFIX."phone_numbers`
                    ADD PRIMARY KEY (`id`),
                    ADD UNIQUE KEY `unique_nickname` (`nickname`),
                    ADD UNIQUE KEY `unique_phone_numbers` (`phone_numbers`) USING HASH;
                ALTER TABLE `".TABLE_PREFIX."phone_numbers`
                    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;";
                $stmt2 = $pdo->prepare($sql2)->execute();
                if($stmt2)
                    $success2 = true;
                $sql3 = "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."upload` (
                    `id` int(11) UNSIGNED NOT NULL,
                    `folder` varchar(100) NOT NULL,
                    `name` varchar(255) NOT NULL,
                    `alt` varchar(255) DEFAULT NULL,
                    `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
                ALTER TABLE `".TABLE_PREFIX."upload`
                    ADD PRIMARY KEY (`id`);
                ALTER TABLE `".TABLE_PREFIX."upload`
                    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;";
                $stmt3 = $pdo->prepare($sql3)->execute();
                if($stmt3)
                    $success3 = true;
                $sql = sprintf("SELECT %s FROM %s",'id',TABLE_PREFIX.'admins');
                $stmp = $pdo->query($sql);
                if($stmp){
                    $res = $stmp->fetchAll();
                    if(count($res)>0)
                        $u->redirect('../'.ADMIN_DIR_NAME.'/signin');
                    
                }
                if($u->post('btn_submit')){
                    try {
                            $step=2;
                            $username = $u->encode($u->post('username'));
                            $password = $u->encode($u->post('password'));
                            $confirm = $u->encode($u->post('confirm'));
                            if($username ==='')
                                $alert = "Please enter username.";
                            else
                            if($password === '')
                                $alert = "Please enter password.";
                            else
                            if($confirm ==='')
                                $alert = "Please enter confirm password.";
                            else
                            if($password !== $confirm)
                                $alert = "Passwords do not match.";
                            else
                            if(!preg_match($forRegex,$confirm)){
                                $alert = '
                                Your password is not strong enough.
                                <div class="text-secondary d-grid passValid">
                                    <span><i></i>Must be 8 to 16 characters.</span>
                                    <span><i></i>Must contain at least 2 number.</span>
                                    <span><i></i>Must contain at least 1 in Capital Case.</span>
                                    <span><i></i>Must contain at least 1 Letter in Small Case.</span>
                                    <span><i></i>Must contain at least 2 Special Character.</span>
                                </div>';
                            }
                            else{
                                $sql = sprintf("INSERT INTO %s (%s, %s, %s) VALUES (?,?,?)",TABLE_PREFIX.'admins','username','password','created_at');
                                $stmp = $pdo->prepare($sql);
                                $stmp->execute([$username,encrypt($confirm),date('Y-m-d H:i:s')]);
                                $u->redirect('../'.ADMIN_DIR_NAME.'/signin');
                            }

                    } catch (Exception $e) {
                        $msg = $e->getMessage();
                    }
                }
            }
        } catch (Exception $e) {
            $msg = "Connect Error! Please set your valid MySQL information, in <b>config.php</b>.";
        }
        $step = (!$success1 || !$success2 || !$success3)?1:$step;

        require_once "setup_html.php";
    }
} catch (Exception $e) {
    die('Error: ' .$e->getMessage());
}


?>