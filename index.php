<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>E-posta Doğrulama</title>
</head>
<body>
    <form method="post" action="">
        <label for="email">E-posta Adresi:</label>
        <input type="email" id="email" name="email" required>
        <input type="submit" value="Kontrol Et">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];

        function checkEmail($email) {
            list($user, $domain) = explode('@', $email);

            if (getmxrr($domain, $mxHosts, $mxWeights)) {
                foreach ($mxHosts as $host) {
                    $connection = fsockopen($host, 25, $errno, $errstr, 5);
                    if ($connection) {
                        fgets($connection, 1024);
                        fputs($connection, "HELO " . $domain . "\r\n");
                        fgets($connection, 1024);
                        fputs($connection, "MAIL FROM: <test@" . $domain . ">\r\n");
                        fgets($connection, 1024);
                        fputs($connection, "RCPT TO: <" . $email . ">\r\n");
                        $response = fgets($connection, 1024);
                        fputs($connection, "QUIT\r\n");
                        fclose($connection);

                        if (strpos($response, '250') !== false) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                }
            } else {
                return false;
            }
        }

        if (checkEmail($email)) {
            echo "<p>E-posta adresi geçerli.</p>";
        } else {
            echo "<p>E-posta adresi geçersiz.</p>";
        }
    }
    ?>
</body>
</html>
