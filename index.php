<?php

require_once 'DB.php';
require_once 'PersonalBank.php';

$bank = new PersonalBank(1);

if (isset($_POST['btnSend'])) {
    $amount = $_POST['amount'];
    $balance = $bank->deposit($amount);
}

if (!isset($balance)) {
    $balance = $bank->getBalance();
}

$details = $bank->getAllDetail();

?>
<form method="post" action="index.php">
    交易金額：<input type="number" name="amount" />
    <button type="submit" name="btnSend">送出</button>
</form>
<p>
    使用者餘額：<?php echo $balance; ?>
</p>
<div>
    <table border="1">
        <tr>
            <th>序號</th>
            <th>交易時間</th>
            <th>存</th>
            <th>提</th>
            <th>餘額</th>
        </tr>
        <?php foreach ($details as $detail) { ?>
            <tr>
                <td><?php echo $detail['id']; ?></td>
                <td><?php echo $detail['created_at']; ?></td>
                <?php
                    if ($detail['amount'] > 0) {
                        printf('<td>%s</td><td></td>', $detail['amount']);
                    } else {
                        printf('<td></td><td>%s</td>', $detail['amount'] * -1);
                    }
                ?>
                <td><?php echo $detail['balance']; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>