<?php

$aConfig = parse_ini_file("/run/secrets/config.ini", true);

require "vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

$sQuery = "SELECT fm.fm_id, fm.fm_text FROM f_full_messages fm WHERE fm.fm_valid = 1 AND fm.fm_delivred IS NULL ORDER BY rand() LIMIT 1";
$sUpdate = "UPDATE f_full_messages fm SET fm.fm_delivred = NOW() WHERE fm.fm_id = :fm_id";

try{
    $oPDO = new PDO('mysql:host='.$aConfig["MYSQL"]["host"].';dbname='.$aConfig["MYSQL"]["database"].';charset=utf8', $aConfig["MYSQL"]["user"], $aConfig["MYSQL"]["password"]);
    $oQuery = $oPDO->prepare($sQuery);
    $oQuery->execute();
    $aMessage = $oQuery->fetch();
    $oQuery->closeCursor();
} catch (PDOException $e) {
    $aMessage = false;
    echo 'Connection fail : ' . $e->getMessage();
}

if(!empty($aMessage)){


    $iFm_ID = $aMessage["fm_id"];
    $sTweet = $aMessage["fm_text"];

    $oUpdate = $oPDO->prepare($sUpdate);
    $oUpdate->bindParam("fm_id",$iFm_ID);
    $oUpdate->execute();
    $oUpdate->closeCursor();

    $oConnection = new TwitterOAuth($aConfig["TWITTER"]["consumer_key"], $aConfig["TWITTER"]["consumer_secret"], $aConfig["TWITTER"]["access_token"], $aConfig["TWITTER"]["access_token_secret"]);
    $oConnectionInfo = $oConnection->get("account/verify_credentials");

    $oPostedStatus = $oConnection->post("statuses/update", ["status" => $sTweet]);
    if ($oConnection->getLastHttpCode() == 200) {
        echo "Success!\n";
    } else {
        echo "Distribution error !\n";
    }
}else{
    echo "Nothing to send :(\n";
}
