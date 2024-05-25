# PHP Email Validation Script

This project is a simple PHP script to check if an email address is valid for a given domain. It verifies the existence of the email address using DNS and SMTP protocols.

## Features

- HTML form for user input of email address.
- Checks the MX records of the email domain.
- Verifies the validity of the email address via SMTP.

## Requirements

- PHP 5.6 or higher
- A web server (Apache, Nginx, etc.)

## Installation

1. Download or copy the project files.
2. Place the files in the root directory of your web server.
3. Open the `index.php` file in your browser.

## Usage

1. Open the project in your browser.
2. Enter the email address you want to verify in the form.
3. Click the "Check" button.
4. See the message indicating whether the email address is valid or not.

## Example Code

Below is the main PHP code of the project:

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Validation</title>
</head>
<body>
    <form method="post" action="">
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" required>
        <input type="submit" value="Check">
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
            echo "<p>Email address is valid.</p>";
        } else {
            echo "<p>Email address is invalid.</p>";
        }
    }
    ?>
</body>
</html>
