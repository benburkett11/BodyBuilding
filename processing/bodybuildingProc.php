<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 8/14/2015
 * Time: 10:24 PM
 */

function parseWhey( $wheyArray ){
    $sql = "INSERT INTO protein ( title, description, details, rating, supportedGoal, mainIngredient, servings, pricePerServing, percentOff, totalPrice, bodyBuildingPrice )
            VALUES ( :title, :description, :details, :rating, :supportedGoal, :mainIngredient, :servings, :pricePerServing, :percentOff, :totalPrice, :bodyBuildingPrice )";

    $rating = array_shift( explode( ' ', $wheyArray[2] ) );
    $goal = end( explode( ':', $wheyArray[3] ) );
    $mainIngredient = end( explode( ':', $wheyArray[4] ) );
    $servings = trim( end( explode( ':', $wheyArray[5] ) ) );
    $ppServing = end( explode( ':', $wheyArray[6] ) );
    $percentOff = array_shift( explode( '%', $wheyArray[7] ) ) ;
    $vars = array(
        ':title' => $wheyArray['title'],
        ':description' => $wheyArray[0],
        ':details' => $wheyArray[1],
        ':rating' => $rating,
        ':supportedGoal' => $goal,
        ':mainIngredient' => $mainIngredient,
        ':servings' => $servings,
        ':pricePerServing' => $ppServing,
        ':percentOff' => $percentOff,
        ':totalPrice' => $wheyArray[8],
        ':bodyBuildingPrice' => $wheyArray[9]
    );

    $res = pdoInsert( $sql, $vars );
    return $res;

}

?>