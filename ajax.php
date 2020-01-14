<?php
/**
 * @author       Amin Mahmoudi (MasterkinG)
 * @copyright    Copyright (c) 2019 - 2022, MsaterkinG32 Team, Inc. (https://masterking32.com)
 * @link         https://masterking32.com
 * @Github       https://github.com/masterking32/wow-telegram
 * @Description  It's not masterking32 framework !
 */

include 'config.php';
if (empty($_SESSION["CM_Login"])) {
    echo "需要登录!";
    exit();
}
if (empty($_POST['command'])) {
    echo "命令无效!";
    exit();
}
if(is_array($_POST['command']))
{
    echo "命令无效!";
    exit();
}

$result = '没有结果!';
try {
    $conn = new SoapClient(NULL, array(
        '位置' => 'http://' . $soap_connection_info['soap_host'] . ':' . $soap_connection_info['soap_port'] . '/',
        'uri' => $soap_connection_info['soap_uri'],
        '类型' => SOAP_RPC,
        '账号' => $soap_connection_info['soap_user'],
        '密码' => $soap_connection_info['soap_pass']
    ));
    $result = $conn->executeCommand(new SoapParam($_POST['command'], 'command'));
    unset($conn);
} catch (Exception $e) {
    if (!empty(Debug_Mode)) {
        $result = $e;
    } else {
        $result = '在soap上有错误!';
    }
    if (strpos($e, '没有这样的命令') !== false) {
        $result = '没有这样的命令!';
    }
}
$paragraphs = '';
foreach (explode("\n", $result) as $line) {
    if (trim($line)) {
        $paragraphs .= '<p>' . $line . '</p>';
    }
}

echo $paragraphs;