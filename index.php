<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 8/13/2015
 * Time: 7:05 PM
 */
    require_once 'vendor/autoload.php';
    $proteinHeaderArray = array( 'title', 'description', 'rating', 'mainIngredient', 'servings', 'pricePerServing', 'percentOff', 'totalPrice', 'bodyBuildingPrice' );

    $sql = "SELECT title, description, details, rating, supportedGoal, mainIngredient, servings, pricePerServing, percentOff, totalPrice, bodyBuildingPrice
            FROM protein";
    $protein = pdoSelect( $sql );

    $proteinContent = '';
    foreach( $protein as $p ){
        extract( $p );
        $proteinContent .= <<<EOE
            <tr>
                <td>$title</td>
                <td>$description</td>
                <td>$rating</td>
                <td>$mainIngredient</td>
                <td>$servings</td>
                <td>$pricePerServing</td>
                <td>$percentOff%</td>
                <td>$totalPrice</td>
                <td>$bodyBuildingPrice</td>
            </tr>
EOE;

    }

    $title = "Bob's Burgers";
    require_once 'core/top.php';
?>

<div class="container" style="margin-top: 30px;">
    <div class="row">
        <div class="col-md-12">
            <table id="protein">
                <thead>
                <tr>
                    <?php
                        foreach( $proteinHeaderArray as $p )
                            echo "<th>$p</th>";
                    ?>
                </tr>
                </thead>
                <tbody>
                    <?=$proteinContent;?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
    require_once 'core/bottom.php';
?>
<script>
    $( document ).ready( function(){
        $('#protein').DataTable();
    });
</script>
