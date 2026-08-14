<?php
    require_once "../db.inc.php";
    $formFields = [
    [
        'label'       => 'Email Address',
        'type'        => 'email',
        'id'          => 'email',
        'name'        => 'email',
        'placeholder' => 'name@example.com',
        'required'    => true,
        'minlength'   => 3,
    ],
    [
        'label'       => 'Password',
        'type'        => 'password',
        'id'          => 'password',
        'name'        => 'password',
        'placeholder' => '••••••••',
        'required'    => "required",
        'minlength'   => 8,
    ],
    ];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Login</title>
</head>

<body>
<<<<<<< Updated upstream
    <main>
        <h1>FoucsFlow</h1>
        <p>clarity in every task.</p>
        <form method="post">
            <?php foreach ($formFields as $field): ?>
                <div>
                    <label for="<?php echo $field['id'] ?>"><?php echo $field['label'] ?></label>
                    <input type="<?php echo $field['type'] ?>" name="<?php echo $field['name'] ?>" id="<?php echo $field['id'] ?> " placeholder="<?php echo $field['placeholder'] ?>" <?php echo $field['required'] ?>>
                </div>
            <?php endforeach?>
            <button type="submit">Login</button>
            <span>
                <p>Don't have an account?</p>
                <a href="./signup.php">Sign up for free</a>
            </span>
        </form>
    </main>
=======
>>>>>>> Stashed changes
</body>

</html>