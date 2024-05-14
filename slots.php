<?php

$width = 5;
$height = 3;


$symbolProbabilities = [
    'Q' => 0.25,
    'K' => 0.15,
    'J' => 0.5,
    'A' => 0.1
];


$symbols = [];
foreach ($symbolProbabilities as $symbol => $probability) {
    $count = floor($probability * 100);
    for ($i = 0; $i < $count; $i++) {
        $symbols[] = $symbol;
    }
}


shuffle($symbols);


$startCoins = readline("Enter the starting amount of virtual coins: ");
if (!is_numeric($startCoins) || $startCoins <= 0) {
    echo "Invalid input. Please enter a valid positive number.\n";
    exit;
}


$betAmount = readline("Enter the bet amount per single spin: ");
if (!is_numeric($betAmount) || $betAmount <= 0) {
    echo "Invalid input. Please enter a valid positive number.\n";
    exit;
}


$coins = $startCoins;


while ($coins >= $betAmount) {

    $board = [];
    for ($i = 0; $i < $height; $i++) {
        $row = [];
        for ($j = 0; $j < $width; $j++) {
            $randomIndex = rand(0, count($symbols) - 1);
            $row[] = $symbols[$randomIndex];
        }
        $board[] = $row;
    }


    echo "Board:\n";
    foreach ($board as $row) {
        echo implode(", ", $row) . "\n";
    }
    echo "\n";


    $winMultipliers = [
        'Q' => 5,
        'K' => 10,
        'J' => 15,
        'A' => 25
    ];


    $colorGreen = "\033[0;32m";
    $colorReset = "\033[0m";


    $winAmount = 0;
    $horizontalMatches = 0;
    $verticalMatches = 0;
    foreach ($winMultipliers as $symbol => $multiplier) {
        $horizontalMatch = false;
        $verticalMatch = false;


        for ($i = 0; $i < $height; $i++) {
            if (array_count_values($board[$i])[$symbol] === $width) {
                $horizontalMatch = true;
                break;
            }
        }


        for ($j = 0; $j < $width; $j++) {
            $column = array_column($board, $j);
            if (array_count_values($column)[$symbol] === $height) {
                $verticalMatch = true;
                break;
            }
        }

        if ($horizontalMatch) {
            $horizontalMatches++;
        }

        if ($verticalMatch) {
            $verticalMatches++;
        }
    }


    $winAmount = ($horizontalMatches + $verticalMatches) * $betAmount * array_sum($winMultipliers);

    if ($winAmount > 0) {
        echo $colorGreen . "Congratulations! You won!" . $colorReset . "\n";
    }


    $coins += $winAmount - $betAmount;



    echo "Win Amount: $winAmount\n";
    echo "Coins Left: $coins\n\n";

    if ($coins < $betAmount) {
        echo "Game over! You ran out of coins.";
        break;
    } else {

        $input = strtolower(readline("Continue? "));
        if (empty(trim($input))) {

        } else {
            echo "Bye! You have $coins coins left.\n";
            break;
        }
    }
}
