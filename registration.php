<?php
// $file = fopen("data.txt", "r+") or die("Unable to open file!");
require_once("task3.php");
$file = 'data.txt';
if (!file_exists($file)) {
    touch('data.txt');
    chmod("data.txt", 666);
} else {
    chmod("data.txt", 666);
}
$success = false;
$error = [];

$datePattern = "/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/";

if (filter_has_var(INPUT_POST, 'submit') && $_SERVER["REQUEST_METHOD"] === "POST") {
    $title = test_input($_POST["title"]);
    $firstName = test_input($_POST["firstName"]);
    $middleName =  isset($_POST["middleName"]) ? test_input($_POST["middleName"]) : "";
    $lastName = test_input($_POST["lastName"]);
    $age = test_input($_POST["age"]);
    $email = test_input($_POST["email"]);
    $phone = isset($_POST["phone"]) ? test_input($_POST["phone"]) : "";
    $date = isset($_POST["arrival-date"]) ? htmlspecialchars($_POST["arrival-date"]) : "";

    if (empty($title) || preg_match("/^[a-z ,.]+$/i", $title) === 0) {
        $error[$title] = "can't be empty or Number/special chars";
    }

    if (empty($firstName) || preg_match("/^[a-z ,.']+$/i", $firstName) === 0) {
        $error[$firstName] = "can't be empty or Number/special chars";
    }

    if (!empty($middleName) && preg_match("/^[a-z ,.']+$/i", $middleName) === 0) {
        $error[$middleName] = "can't be empty or Number/special chars";
    }
    if (empty($lastName) || preg_match("/^[a-z ,.']+$/i", $lastName) === 0) {
        $error[$lastName] = "can't be empty or Number/special chars";
    }

    if ($age < 18 || $age > 120) {
        $error[$age] = "Age between 18 or 150 only";
    }

    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (empty($email)) {
        $error[$email] = "Email can't be empty";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    } else {
        $error[$email] = "Not valid";
    }


    if (!empty($phone) && preg_match("/^(\+\d{1,2}\s?)?1?\-?\.?\s?\(?\d{2}\)?[\s.-]?\d{3}[\s.-]?\d{3}$/", $phone) === 0) {
        $error[$phone] = "Phone no. is invalid";
    }


    if (preg_match($datePattern, $date) === 0 || empty($date)) {
        $error[$date] = "Date invalid";
    }

    if (empty($error)) {
        addData($title, $firstName, $middleName, $lastName, $age, $email, $phone, $date);
    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}

function addData($title, $firstName, $middleName, $lastName, $age, $email, $phone, $date)
{
    $newLine = $title . ";" . $firstName . ";" . $middleName . ";" . $lastName . ";" . $age . ";" . $email . ";" . $phone . ";" . $date . PHP_EOL;
    file_put_contents(DATA_FILE, $newLine, FILE_APPEND);
    global $success;
    $success = true;
}

if (!$success) {


?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration Page</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <div class="container" style="margin-top: 50px;">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <label for="title">Title:</label>
                </br>
                <select name="title" id="title">
                    <option value="Mr.">Mr.</option>
                    <option value="Ms.">Ms.</option>
                    <option value="Mrs.">Mrs.</option>
                    <option value="Sir">Sir</option>
                    <option value="Prof">Prof</option>
                    <option value="Dr">Dr</option>
                </select>
                </br>

                <label for="firstName">First Name:</label>
                </br>
                <input type="text" name="firstName" placeholder="First Name" required>
                </br>

                <label for="middleName">Middle Name:</label>
                </br>
                <input type="text" name="middleName" placeholder="Middle Name">
                </br>

                <label for="lastName">Last Name: </label>
                </br>
                <input type="text" name="lastName" placeholder="Last Name" required>
                </br>

                <label for="age">Age: </label>
                </br>
                <input type="number" pattern="^[0-9]*$" name="age" id="age" min="18" max="120" placeholder="Your Age" required>
                </br>

                <label for="email">Email: </label>
                </br>
                <input type="email" pattern="^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$" name="email" placeholder="Your Email" required>
                </br>

                <label for="phone">Contact Phone: </label>
                </br>
                <input type="number" pattern="^[0-9]{8}*$" name="phone" placeholder="Your phone no">
                </br>
                <label for="date">Arrival Date:</label>
                </br>
                <input type="date" id="date" name="arrival-date" required>
                </br>
                <input type="submit" name="submit" value="Submit">
            </form>
        </div>
    </body>

    </html>
<?php
} else {
    $registrants = array_reverse(showRegistrants());
    $numOfReg = count(showRegistrants());
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css">
        <title>Registration Confirmation</title>
    </head>

    <body>
        <div class="container" style="margin-top: 50px;">
            <div>
                <div class="alert alert-success">Your registration has been done!</div>
                <p>Name: <?= $registrants[0]["title"] . " " . $registrants[0]["firstName"] . " " . $registrants[0]["middleName"] . " " . $registrants[0]["lastName"] ?> </p>
                <p>Age: <?= $registrants[0]["age"] ?> </p>
                <p>Email: <?= $registrants[0]["email"] ?> </p>
                <p>Phone: <?= $registrants[0]["phone"] ?> </p>
                <p>Date: <?= $registrants[0]["date"] ?> </p>
            </div>
            <div>
                <h3>Total Registered Users: <?= $numOfReg ?></h3>
                <button type="button" class="btn btn-primary">
                    <a href="data.txt" style="color: white; text-decoration: none;" download>Download Data File</a>
                </button>
                </br>
                </br>
                <button type="button" class="btn btn-secondary">
                    <a href="./registration.php" style="color: white; text-decoration: none;">Fill another form</a>
                </button>
            </div>
        </div>
    </body>

    </html>
<?php
}
?>