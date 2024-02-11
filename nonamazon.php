<?php 
$title = "Switch statements";
include 'includes/header.php' 
?>

    <h1> Non Amazon </h1>
    <?php 
        $grade = "B";

        switch($grade){
            case 'A':
                echo '<h2>YOU ARE A SUPERSTAR</H2>';
                break;
            case 'B':
                echo '<h2 style="color: blue"> YOU DID WELL</H2>';
                break;
            default:
                echo '<h2 style="color: RED"> YOU HAVE FAILED</H2>';
                break;

        }
    
    ?>
    <?php require 'includes/footer.php' ?>
