<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$tokenFile = 'token.json';
$tokens = [];
if (file_exists($tokenFile)) {
    $tokens = json_decode(file_get_contents($tokenFile), true);
} else {
    die("Token file not found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentname = $_POST['studentname'];
    $studentid = $_POST['studentid'];
    $email = $_POST['email']; 
    $bt = $_POST['booktitle'];
    $bd = $_POST['borrowdate'];
    $rn = $_POST['returndate'];
    $tn = $_POST['token'];
    $fees = $_POST['fees'];

    $errors = [];
    $borrowDate = strtotime($bd);
    $returnDate = strtotime($rn);

    // Validation
    if (empty($studentname) || !preg_match('/^[a-zA-Z\s]+$/', $studentname)) {
        $errors[] = 'Student Name is invalid';
    }
    if (empty($studentid) || !preg_match('/^\d{2}-\d{5}-\d{1}$/', $studentid)) {
        $errors[] = 'Invalid Student ID format';
    }
    if (empty($email) || !preg_match('/^\d{2}-\d{5}-\d@student\.aiub\.edu$/', $email)) {
        $errors[] = 'Invalid email format';
    }
    if (!$borrowDate || !$returnDate || $returnDate <= $borrowDate) {
        $errors[] = 'Invalid Borrow/Return Dates';
    }
    $dateDiff = ($returnDate - $borrowDate) / 86400;
    if ($dateDiff > 10 || $dateDiff <= 0) {
        $errors[] = 'Borrow period must be between 1 and 10 days';
    }
    if (!ctype_digit($tn)) {
        $errors[] = 'Token Number must contain only numbers';
    }
    if (!is_numeric($fees) || $fees <= 0) {
        $errors[] = 'Fees must be a positive number';
    }

    // Check token validity
    $tokenValid = false;
    foreach ($tokens as $key => $token) {
        if ($token['token'] == $tn) {
            $tokenValid = true;
            unset($tokens[$key]); // Remove the used token
            break;
        }
    }

    if (!$tokenValid) {
        $errors[] = 'Invalid or expired token';
    }

    // Check for duplicate book borrowing
    if (isset($_COOKIE["borrowed_books"])) {
        $borrowedBooks = json_decode($_COOKIE["borrowed_books"], true);
        if (in_array($bt, $borrowedBooks)) {
            $errors[] = "You can't borrow '$bt' as it's already taken.";
        }
    } else {
        $borrowedBooks = [];
    }

    if ($errors) {
        foreach ($errors as $error) {
            echo '<b style="color: red;">Error: ' . $error . '</b><br>';
        }
    } else {
        // Save the updated tokens
        file_put_contents($tokenFile, json_encode(array_values($tokens)));

        // Save used token to a log
        $usedTokensFile = 'used_tokens.json';
        $usedTokens = file_exists($usedTokensFile) ? json_decode(file_get_contents($usedTokensFile), true) : [];
        $usedTokens[] = ['token' => $tn];
        file_put_contents($usedTokensFile, json_encode($usedTokens));

        // Add book to the borrowed list
        $borrowedBooks[] = $bt;
        setcookie("borrowed_books", json_encode($borrowedBooks), time() + 86400, "/");

        // Display Information
        echo "<div style='font-family: Arial, sans-serif; margin: 20px auto; width: 70%; background-color: #f8f9fa; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);'>";
            echo "<h2 style='text-align: center; font-size: 2.5rem; color: #333; margin-bottom: 20px;'>Borrower Information</h2>";
            echo "<table style='width: 100%; border-collapse: collapse; margin: auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); overflow: hidden;'>";
            echo "<thead style='background-color: #007BFF; color: white; text-align: center;'>
                    <tr>
                        <th style='padding: 15px; font-size: 1.2rem;'>Item</th>
                        <th style='padding: 15px; font-size: 1.2rem;'>Details</th>
                    </tr>
                </thead>";
            echo "<tbody>";
            echo "<tr>
                    <td style='padding: 15px; border-bottom: 1px solid #ddd; background-color: #f9f9f9;'>Student Name</td>
                    <td style='padding: 15px; border-bottom: 1px solid #ddd; background-color: #f9f9f9;'>$studentname</td>
                </tr>";
            echo "<tr>
                    <td style='padding: 15px; border-bottom: 1px solid #ddd; background-color: #f1f1f1;'>Student ID</td>
                    <td style='padding: 15px; border-bottom: 1px solid #ddd; background-color: #f1f1f1;'>$studentid</td>
                </tr>";
            echo "<tr>
                    <td style='padding: 15px; border-bottom: 1px solid #ddd; background-color: #f9f9f9;'>Email</td>
                    <td style='padding: 15px; border-bottom: 1px solid #ddd; background-color: #f9f9f9;'>$email</td>
                </tr>";
            echo "<tr>
                    <td style='padding: 15px; border-bottom: 1px solid #ddd; background-color: #f1f1f1;'>Book Title</td>
                    <td style='padding: 15px; border-bottom: 1px solid #ddd; background-color: #f1f1f1;'>$bt</td>
                </tr>";
            echo "<tr>
                    <td style='padding: 15px; border-bottom: 1px solid #ddd; background-color: #f9f9f9;'>Borrow Date</td>
                    <td style='padding: 15px; border-bottom: 1px solid #ddd; background-color: #f9f9f9;'>$bd</td>
                </tr>";
            echo "<tr>
                    <td style='padding: 15px; border-bottom: 1px solid #ddd; background-color: #f1f1f1;'>Return Date</td>
                    <td style='padding: 15px; border-bottom: 1px solid #ddd; background-color: #f1f1f1;'>$rn</td>
                </tr>";
            echo "<tr>
                    <td style='padding: 15px; border-bottom: 1px solid #ddd; background-color: #f9f9f9;'>Token Number</td>
                    <td style='padding: 15px; border-bottom: 1px solid #ddd; background-color: #f9f9f9;'>$tn</td>
                </tr>";
            echo "<tr>
                    <td style='padding: 15px; background-color: #f1f1f1;'>Fees</td>
                    <td style='padding: 15px; background-color: #f1f1f1;'>$fees</td>
                </tr>";
            echo "</tbody>";
            echo "</table>";
            echo "<div style='text-align: center; margin-top: 30px;'>
                    <button style='background-color:rgb(125, 148, 219); color: white; border: none; padding: 12px 24px; margin: 10px; cursor: pointer; border-radius: 5px; font-size: 1rem; transition: 0.3s;'
                            onmouseover=\"this.style.backgroundColor='rgb(35, 14, 140)'\"
                            onmouseout=\"this.style.backgroundColor='rgb(56, 25, 210)''\"
                            onclick='window.print()'>Print Details </button>
                    <button style='background-color:rgb(74, 89, 102); color: white; border: none; padding: 12px 24px; margin: 10px; cursor: pointer; border-radius: 5px; font-size: 1rem; transition: 0.3s;'
                            onmouseover=\"this.style.backgroundColor='rgb(56, 83, 113)''\"
                            onmouseout=\"this.style.backgroundColor='rgb(79, 110, 130)''\"
                            onclick=\"window.location.href='index.php'\">Back</button>
                </div>";
            echo "</div>";

        
    }
} else {
    echo '<b>Error: Invalid request method</b>';
}
?>
