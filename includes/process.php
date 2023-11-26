<?php

// requires
require_once 'conn.php';
require_once "sendmail.php";

session_start();

$response = array();

// Registration
if (isset($_POST['Register'])) {
    $FirstName = $conn->real_escape_string($_POST['FirstName']);
    $MiddleName = $conn->real_escape_string($_POST['MiddleName']);
    $LastName = $conn->real_escape_string($_POST['LastName']);
    $Email = $conn->real_escape_string($_POST['Email']);
    $Phone = $conn->real_escape_string($_POST['Phone']);
    $Address = $conn->real_escape_string($_POST['Address']);
    $Username = $conn->real_escape_string($_POST['Username']);
    $Password = $conn->real_escape_string($_POST['Password']);

    $HashedPassword = password_hash($Password, PASSWORD_DEFAULT);
    $query = "INSERT INTO `users` (`FirstName`,`MiddleName`,`LastName`,`Email`,`Phone`,`Address`,`Username`,`Password`) VALUES (?,?,?,?,?,?,?,?)";
    try {

        $result = $conn->execute_query($query, [$FirstName, $MiddleName, $LastName, $Email, $Phone, $Address, $Username, $HashedPassword]);

        if ($result) {

            $response['status'] = 'success';
            $response['message'] = 'Registration successful!';
            $response['redirect'] = '../Homepage/index.html';
        } else {

            $response['status'] = 'error';
            $response['message'] = 'Registration failed!';
        }
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
    }
}

// Login
if (isset($_POST['Login'])) {
    $Role = $conn->real_escape_string($_POST['Login']);
    $Username = $conn->real_escape_string($_POST['Username']);
    $Password = $conn->real_escape_string($_POST['Password']);

    $query = "SELECT * FROM `users` where `Username`=? AND `Role`=?";

    try {
        $result = $conn->execute_query($query, [$Username, $Role]);

        if ($result && $result->num_rows === 1) {

            $row = $result->fetch_object();

            if (password_verify($Password, $row->Password)) {

                $_SESSION['Username'] = $Username;
                $_SESSION['Role'] = $row->Role;

                $response['status'] = 'success';
                $response['message'] = 'Login successful!';
                $response['redirect'] = '../' . $Role . '/dashboard.php';
            } else {

                $response['status'] = 'error';
                $response['message'] = 'Invalid Password!';
            }
        } else {

            $response['status'] = 'error';
            $response['message'] = 'Username not found!';
        }
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
    }
}

if (isset($_POST['ForgotPassword'])) {
    $Email = $conn->real_escape_string($_POST['Email']);

    $query = "SELECT * FROM users WHERE `Email` = ?";
    $result = $conn->execute_query($query, [$Email]);

    if ($result->num_rows > 0) {
        $ChangePassword = substr(strtoupper(uniqid()), 0, 8);
        $HashedPassword = password_hash($ChangePassword, PASSWORD_DEFAULT);

        $query2 = "UPDATE users SET `Password` = ?, `ChangePassword` = ? WHERE `Email` = ?";
        $result2 = $conn->execute_query($query2, [$HashedPassword, $ChangePassword, $Email]);

        if ($result2) {
            while ($row = $result->fetch_object()) {
                $Subject = "ISAT-U System | Password Reset Request";
                $Message = "Hello " . $row->FirstName . " " . $row->LastName . ",<br><br>";
                $Message .= "We received a request to reset your password. If you didn't make this request, you can ignore this email. Otherwise, please login using the provided password to reset your previous password:<br><br>";
                $Message .= "Reset Password: " . $ChangePassword . "<br><br>";
                $Message .= "The password will expire in 120 seconds.<br><br>";
                $Message .= "If you have any questions or need further assistance, please don't hesitate to contact us.<br><br>";
                $Message .= "Thank you for choosing our service!<br><br>";
                $Message .= "Sincerely, ISAT-U Registrar's Online Transaction and E-Payment System<br>";
                sendEmail($row->Email, $Subject, $Message);

                $response['status'] = 'success';
                $response['message'] = 'Temporary Password Sent!';
                $response['redirect'] = '../Homepage/index.html';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Password update failed!';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Email does not exist!';
    }
}


// Update Profile
if (isset($_POST['UpdateProfile'])) {
    $FirstName = $conn->real_escape_string($_POST['FirstName']);
    $MiddleName = $conn->real_escape_string($_POST['MiddleName']);
    $LastName = $conn->real_escape_string($_POST['LastName']);
    $Email = $conn->real_escape_string($_POST['Email']);
    $Phone = $conn->real_escape_string($_POST['Phone']);
    $Address = $conn->real_escape_string($_POST['Address']);
    $IDNo = $conn->real_escape_string($_POST['IDNo']) ?? NULL;
    $Course = $conn->real_escape_string($_POST['Course']) ?? NULL;
    $Year = $conn->real_escape_string($_POST['Year']) ?? NULL;
    $Section = $conn->real_escape_string($_POST['Section']) ?? NULL;
    $AcademicStatus = $conn->real_escape_string($_POST['AcademicStatus']) ?? NULL;

    $query = "UPDATE `users` SET `FirstName`=?,`MiddleName`=?,`LastName`=?,`Email`=?,`Phone`=?,`Address`=? WHERE `id`=?";

    try {

        $result = $conn->execute_query($query, [$FirstName, $MiddleName, $LastName, $Email, $Phone, $Address, $_SESSION['id']]);
        if ($_SESSION['Role'] == 'Student') {
            $query2 = "UPDATE `students`SET `IDNo`=?,`Course`=?,`Year`=?,`Section`=?,`AcademicStatus`=? WHERE `UserID`=?";
            $result2 = $conn->execute_query($query2, [$IDNo, $Course, $Year, $Section, $AcademicStatus, $_SESSION['id']]);
        }

        if ($result) {

            $response['status'] = 'success';
            $response['message'] = 'Profile Updated!';
            $response['redirect'] = '../' . $_SESSION['Role'] . '/profile.php';
        } else {

            $response['status'] = 'error';
            $response['message'] = 'Failed Updating Profile!';
        }
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
    }
}

// Update Password
if (isset($_POST['UpdatePassword'])) {
    $CurrentPassword = $conn->real_escape_string($_POST['CurrentPassword']);
    $NewPassword = $conn->real_escape_string($_POST['NewPassword']);
    $VerifyPassword = $conn->real_escape_string($_POST['VerifyPassword']);

    $query = "SELECT * FROM users where Username=?";

    try {
        $result = $conn->execute_query($query, [$_SESSION['Username']]);

        if ($result && $result->num_rows === 1) {

            $row = $result->fetch_object();

            if (password_verify($CurrentPassword, $row->Password)) {
                if ($NewPassword == $VerifyPassword) {
                    $HashedPassword = password_hash($NewPassword, PASSWORD_DEFAULT);
                    $query2 = "UPDATE `users` SET `Password`=? WHERE `Username`=?";
                    try {

                        $result2 = $conn->execute_query($query2, [$HashedPassword, $_SESSION["Username"]]);

                        if ($result2) {

                            $response['status'] = 'success';
                            $response['message'] = 'Password Changed!';
                            $response['redirect'] = '../' . $_SESSION['Role'] . '/profile.php';
                        } else {

                            $response['status'] = 'error';
                            $response['message'] = 'Failed changing password!';
                        }
                    } catch (Exception $e) {
                        $response['status'] = 'error';
                        $response['message'] = $e->getMessage();
                    }
                } else {

                    $response['status'] = 'error';
                    $response['message'] = 'Password don\'t match!';
                }
            } else {

                $response['status'] = 'error';
                $response['message'] = 'Invalid Password!';
            }
        } else {

            $response['status'] = 'error';
            $response['message'] = 'Username not found!';
        }
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
    }
}
if (isset($_POST['ChangePassword'])) {
    $NewPassword = $conn->real_escape_string($_POST['NewPassword']);
    $VerifyPassword = $conn->real_escape_string($_POST['VerifyPassword']);

    $query = "SELECT * FROM users where Username=?";

    try {
        $result = $conn->execute_query($query, [$_SESSION['Username']]);

        if ($result && $result->num_rows === 1) {
            if ($NewPassword == $VerifyPassword) {
                $HashedPassword = password_hash($NewPassword, PASSWORD_DEFAULT);
                $query2 = "UPDATE `users` SET `Password` = ?, `ChangePassword` = NULL WHERE `Username` = ?";
                try {

                    $result2 = $conn->execute_query($query2, [$HashedPassword, $_SESSION["Username"]]);

                    if ($result2) {

                        $response['status'] = 'success';
                        $response['message'] = 'Password Changed!';
                        $response['redirect'] = '../' . $_SESSION['Role'] . '/dashboard.php';
                    } else {

                        $response['status'] = 'error';
                        $response['message'] = 'Failed changing password!';
                    }
                } catch (Exception $e) {
                    $response['status'] = 'error';
                    $response['message'] = $e->getMessage();
                }
            } else {

                $response['status'] = 'error';
                $response['message'] = 'Password don\'t match!';
            }
        } else {

            $response['status'] = 'error';
            $response['message'] = 'Username not found!';
        }
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
    }
}

if (isset($_POST['Student'])) {
    try {
        $id = $conn->real_escape_string($_POST['Student']);
        $IDNo = $conn->real_escape_string($_POST['IDNo']);
        $Course = $conn->real_escape_string($_POST['Course']);
        $Year = $conn->real_escape_string($_POST['Year']);
        $Section = $conn->real_escape_string($_POST['Section']);
        $AcademicStatus = $conn->real_escape_string($_POST['AcademicStatus']);

        $query = "INSERT INTO students(`UserID`,`IDNo`,`Course`,`Year`,`Section`,`AcademicStatus`) VALUES(?,?,?,?,?,?)";
        $result = $conn->execute_query($query, [$id, $IDNo, $Course, $Year, $Section, $AcademicStatus]);

        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Student Profile Updated!';
        } else {
            throw new Exception('Error inserting into students table.');
        }
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
    }
}

if (isset($_POST['Save'])) {
    try {
        $PaymentID = $conn->real_escape_string($_POST['Save']);
        $Remarks = $conn->real_escape_string($_POST['Remarks']);
        $AssistedBy = $conn->real_escape_string($_POST['AssistedBy']);
        $ReleasedBy = $conn->real_escape_string($_POST['ReleasedBy']);

        // Your SQL query
        $query = "UPDATE payments SET Remarks=?, AssistedBy=?, ReleasedBy=? WHERE `id`=?";

        // Use the execute_query function
        $result = $conn->execute_query($query, [$Remarks, $AssistedBy, $ReleasedBy, $PaymentID]);

        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Transaction Updated!';
        } else {
            throw new Exception('Error updating database.');
        }
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
    }
}

if (isset($_POST['ContactUs'])) {
    $Name = $conn->real_escape_string($_POST['Name']);
    $Email = $conn->real_escape_string($_POST['Email']);
    $Subject = $conn->real_escape_string($_POST['Subject']);
    $Message = $conn->real_escape_string($_POST['Message']);

    if (sendEmail('dace.phage@gmail.com', $Subject, 'Senders Name: ' . $Name . '<br>Senders Email: ' . $Email . '<br><br>' . $Message)) {
        $response['status'] = 'success';
        $response['message'] = 'Email Sent!';
        $response['redirect'] = '../index.html';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Unable to Send Email!';
    }

}

if (isset($_POST['Approve'])) {
    try {
        $PaymentID = $conn->real_escape_string($_POST['Approve']);
        $Remarks = $conn->real_escape_string($_POST['Remarks']);
        $AssistedBy = $conn->real_escape_string($_POST['AssistedBy']);
        $ReleasedBy = $conn->real_escape_string($_POST['ReleasedBy']);

        $query = "UPDATE payments SET Remarks=?, AssistedBy=?, ReleasedBy=?, Status='Processing' WHERE id=?";
        $result = $conn->execute_query($query, [$Remarks, $AssistedBy, $ReleasedBy, $PaymentID]);

        $query = "SELECT * FROM payments WHERE `id`=?";
        $result = $conn->execute_query($query, [$PaymentID]);
        while ($row = $result->fetch_object()) {
            $query2 = "INSERT INTO paymentshistory(`UserID`,`PaymentNo`,`ReferenceNo`,`Total`, `Status`) VALUES(?,?,?,?,?)";
            $result2 = $conn->execute_query($query2, [$row->UserID, $row->PaymentNo, $row->ReferenceNo, $row->Total, 'Processing']);
        }

        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Payment Approved!';
        } else {
            throw new Exception('Error updating database.');
        }
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
    }
}

if (isset($_POST['ToClaim'])) {
    try {
        $PaymentID = $conn->real_escape_string($_POST['ToClaim']);
        $Remarks = $conn->real_escape_string($_POST['Remarks']);
        $AssistedBy = $conn->real_escape_string($_POST['AssistedBy']);
        $ReleasedBy = $conn->real_escape_string($_POST['ReleasedBy']);

        $query = "UPDATE payments SET Remarks=?, AssistedBy=?, ReleasedBy=?, `Status`='Completed' WHERE `id`=?";
        $result = $conn->execute_query($query, [$Remarks, $AssistedBy, $ReleasedBy, $PaymentID]);

        $query = "SELECT * FROM payments WHERE `id`=?";
        $result = $conn->execute_query($query, [$PaymentID]);
        while ($row = $result->fetch_object()) {
            $query2 = "INSERT INTO paymentshistory(`UserID`,`PaymentNo`,`ReferenceNo`,`Total`, `Status`) VALUES(?,?,?,?,?)";
            $result2 = $conn->execute_query($query2, [$row->UserID, $row->PaymentNo, $row->ReferenceNo, $row->Total, 'Completed']);
        }



        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Document Processed!';
        } else {
            throw new Exception('Error updating database.');
        }
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
    }
}

if (isset($_POST['Request'])) {
    $id = $conn->real_escape_string($_POST['Request']);

    $PaymentNo = $conn->real_escape_string($_POST["PaymentNo"]);
    $ReferenceNo = $conn->real_escape_string($_POST["ReferenceNo"]);
    $Total = $conn->real_escape_string($_POST["Total"]);

    // Insert into payments table
    $query = "INSERT INTO payments(`UserID`,`PaymentNo`,`ReferenceNo`,`Total`) VALUES(?,?,?,?)";
    $result = $conn->execute_query($query, [$id, $PaymentNo, $ReferenceNo, $Total]);

    $query = "INSERT INTO paymentshistory(`UserID`,`PaymentNo`,`ReferenceNo`,`Total`) VALUES(?,?,?,?)";
    $result = $conn->execute_query($query, [$id, $PaymentNo, $ReferenceNo, $Total]);

    if ($result) {
        $PaymentID = $conn->insert_id;

        // Insert into requests table for documents
        $query = "SELECT * FROM documents";
        $result = $conn->execute_query($query);
        while ($row = $result->fetch_object()) {
            if (isset($_POST["document_" . $row->id])) {
                $query2 = "INSERT INTO requests(`DocumentID`, `PaymentID`) VALUES(?,?)";
                $result2 = $conn->execute_query($query2, [$_POST["document_" . $row->id], $PaymentID]);

                if (!$result2) {
                    $response['status'] = 'error';
                    $response['message'] = "Error inserting into requests table for document ID " . $row->id;
                }
            }
        }

        // Insert into requests table for purposes
        $query = "SELECT * FROM purposes";
        $result = $conn->execute_query($query);
        while ($row = $result->fetch_object()) {
            if (isset($_POST["purpose_" . $row->id])) {
                $query2 = "INSERT INTO requests(`PurposeID`, `PaymentID`) VALUES(?,?)";
                $result2 = $conn->execute_query($query2, [$_POST["purpose_" . $row->id], $PaymentID]);

                if (!$result2) {
                    $response['status'] = 'error';
                    $response['message'] = "Error inserting into requests table for purpose ID " . $row->id;
                }
            }
        }

        // Insert into requests table for requirements
        $query = "SELECT * FROM requirements";
        $result = $conn->execute_query($query);
        while ($row = $result->fetch_object()) {
            if (isset($_POST["requirement_" . $row->id])) {
                $query2 = "INSERT INTO requests(`RequirementID`, `PaymentID`) VALUES(?,?)";
                $result2 = $conn->execute_query($query2, [$_POST["requirement_" . $row->id], $PaymentID]);

                if (!$result2) {
                    $response['status'] = 'error';
                    $response['message'] = "Error inserting into requests table for requirement ID " . $row->id;
                }
            }
        }

        $response['status'] = 'success';
        $response['message'] = 'Payment and requests added successfully';
        $response['redirect'] = '../Student/requests.php';
    } else {
        $response['status'] = 'error';
        $response['message'] = "Error inserting into payments table";
    }
}
// Modify User
if (isset($_POST['AddUser'])) {
    $FirstName = $conn->real_escape_string($_POST['FirstName']);
    $MiddleName = $conn->real_escape_string($_POST['MiddleName']);
    $LastName = $conn->real_escape_string($_POST['LastName']);
    $Username = $conn->real_escape_string($_POST['Username']);
    $Email = $conn->real_escape_string($_POST['Email']);
    $Phone = $conn->real_escape_string($_POST['Phone']);
    $Address = $conn->real_escape_string($_POST['Address']);
    $Role = $conn->real_escape_string($_POST['Role']);
    $Password = substr(strtoupper(uniqid()), 0, 8);

    $HashedPassword = password_hash($Password, PASSWORD_DEFAULT);

    $query = "INSERT INTO `users`(`FirstName`, `MiddleName`, `LastName`, `Username`, `Password`, `Email`, `Phone`, `Address`, `Role`, `ChangePassword`) VALUES(?,?,?,?,?,?,?,?,?,?)";
    try {
        $result = $conn->execute_query($query, [$FirstName, $MiddleName, $LastName, $Username, $HashedPassword, $Email, $Phone, $Address, $Role, $Password]);
        if ($result) {

            $Subject = 'Your Registrar System Account Information';

            $Message = "Hello " . $FirstName . " " . $LastName . ",<br><br>";
            $Message .= "Your account has been created. Here are your login details:<br><br>";
            $Message .= "Username: " . $Username . "<br>";
            $Message .= "Password: " . $Password . "<br><br>";
            $Message .= "You can now use these credentials to log in to your account.<br><br>";
            $Message .= "If you have any questions or need further assistance, please don't hesitate to contact us.<br><br>";
            $Message .= "Thank you for choosing our service!<br><br>";
            $Message .= "Sincerely, ISAT-U Admin<br>";
            $Message .= "ISAT-U";

            sendEmail($Email, $Subject, $Message);

            $response['status'] = 'success';
            $response['message'] = 'New User Inserted!';
            $response['redirect'] = $_SESSION['Role'] == 'Admin' ? '../Admin/users.php' : '../Staff/users.php';
        } else {

            $response['status'] = 'error';
            $response['message'] = 'Adding failed!';
        }
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
    }
}

if (isset($_POST['UpdateUser'])) {
    $id = $conn->real_escape_string($_POST['id']);
    $FirstName = $conn->real_escape_string($_POST['FirstName']);
    $MiddleName = $conn->real_escape_string($_POST['MiddleName']);
    $LastName = $conn->real_escape_string($_POST['LastName']);
    $Username = $conn->real_escape_string($_POST['Username']);
    $Email = $conn->real_escape_string($_POST['Email']);
    $Phone = $conn->real_escape_string($_POST['Phone']);
    $Address = $conn->real_escape_string($_POST['Address']);
    $Role = $conn->real_escape_string($_POST['Role']);

    $query = "UPDATE `users` SET `FirstName`=?, `MiddleName`=?, `LastName`=?, `Username`=?, `Email`=?, `Phone`=?, `Address`=?, `Role`=?";

    $query .= " WHERE `id`=?";

    try {
        $params = [$FirstName, $MiddleName, $LastName, $Username, $Email, $Phone, $Address, $Role, $id];

        $result = $conn->execute_query($query, $params);

        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'User Updated!';
            $response['redirect'] = $_SESSION['Role'] == 'Admin' ? '../Admin/users.php' : '../Staff/users.php';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Update failed!';
        }
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
    }
}


if (isset($_GET['DeleteUser'])) {

    $query = "DELETE FROM users WHERE `id` = ?";
    try {
        $result = $conn->execute_query($query, [$_GET['DeleteUser']]);
        if ($result) {

            $response['status'] = 'success';
            $response['message'] = 'User Deleted!';
            $response['redirect'] = $_SESSION['Role'] == 'Admin' ? '../Admin/users.php' : '../Staff/users.php';
        } else {

            $response['status'] = 'error';
            $response['message'] = 'Delete failed!';
        }
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
    }
}

if (isset($_GET['ResetPassword'])) {
    $ChangePassword = substr(strtoupper(uniqid()), 0, 8);
    $HashedPassword = password_hash($ChangePassword, PASSWORD_DEFAULT);
    $query = "UPDATE users SET `Password` = ?, `ChangePassword` = ? WHERE `id` = ?";
    $result = $conn->execute_query($query, [$HashedPassword, $ChangePassword, $_GET['ResetPassword']]);

    if ($result) {
        $query2 = "SELECT * FROM users WHERE `id` = ?";
        $result2 = $conn->execute_query($query2, [$_GET['ResetPassword']]);
        while ($row = $result2->fetch_object()) {
            sendEmail($row->Email, 'ISAT-U Registrar System Password Reset Request', "Hello " . $row->FirstName . " " . $row->LastName . ",\n\nWe received a request to reset your password. If you didn't make this request, you can ignore this email. Otherwise, please login using the provided password to reset your previous password:\n\nReset Password: " . $ChangePassword . "\n\nThe password will expire in 120 seconds.\n\nIf you have any questions or need further assistance, please don't hesitate to contact us.\n\nThank you for choosing our service!\n\nSincerely, ISAT-U Admin\nISAT-U");

            $response['status'] = 'success';
            $response['message'] = 'Reset Password Sent!';
            $response['redirect'] = $_SESSION['Role'] == 'Admin' ? '../Admin/users.php' : '../Staff/users.php';
        }
    } else {

        $response['status'] = 'error';
        $response['message'] = 'Adding failed!';
    }
}
// Modify User
if (isset($_POST['AddAnnouncement'])) {
    $Title = $conn->real_escape_string($_POST['Title']);
    $Description = $conn->real_escape_string($_POST['Description']);
    $Author = $conn->real_escape_string($_POST['Author']);

    $query = "INSERT INTO `announcements`(`Title`, `Description`, `Author`) VALUES(?,?,?)";
    try {
        $result = $conn->execute_query($query, [$Title, $Description, $Author]);
        if ($result) {

            $response['status'] = 'success';
            $response['message'] = 'New Announcement Posted!';
            $response['redirect'] = $_SESSION['Role'] == 'Admin' ? '../Admin/contents.php' : '../Staff/contents.php';
        } else {

            $response['status'] = 'error';
            $response['message'] = 'Adding failed!';
        }
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
    }
}

$responseJSON = json_encode($response);

// echo $responseJSON;

$conn->close();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Response Page</title>
    <!-- Include SweetAlert library -->
    <style>
        html {
            position: relative;
            min-height: 100%;

            /* Your background image properties */
            background: url(../Homepage/assets/img/isat-bg.jpg) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }

        html::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* Change the color and opacity here */
            z-index: 1;
            /* Adjust the z-index to control the overlay's position */
        }
    </style>
</head>

<body>
    <!-- Bootstrap core JavaScript-->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Parse the JSON response from PHP
        var response = <?php echo $responseJSON; ?>;

        // Display a SweetAlert notification based on the response
        if (response.status == 'success') {
            Swal.fire({
                title: 'Success',
                text: response.message,
                icon: 'success',
            }).then(function () {
                // Redirect to the specified URL
                if (response.redirect) {
                    window.location.href = response.redirect;
                } else {
                    history.back();
                }
            });
        } else if (response.status == 'error') {
            Swal.fire({
                title: 'Error',
                text: response.message,
                icon: 'error',
            }).then(function () {
                // Redirect to the specified URL
                history.back();
            });
        } else {
            Swal.fire({
                title: 'Error',
                text: 'there is something wrong!',
                icon: 'error',
            }).then(function () {
                // Redirect to the specified URL
                history.back();
            });
        }
    </script>
</body>

</html>