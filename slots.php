<?php
// Define board size
$width = 5;
$height = 3;

// Define symbols and their probabilities
$symbolProbabilities = [
    'Q' => 0.25,
    'K' => 0.15,
    'J' => 0.5,
    'A' => 0.1
];

// Generate symbols based on probabilities
$symbols = [];
foreach ($symbolProbabilities as $symbol => $probability) {
    $count = floor($probability * 100); // Convert probability to integer count
    for ($i = 0; $i < $count; $i++) {
        $symbols[] = $symbol;
    }
}

// Shuffle symbols to randomize their order
shuffle($symbols);

// Get start coins from user input
$startCoins = readline("Enter the starting amount of virtual coins: ");
if (!is_numeric($startCoins) || $startCoins <= 0) {
    echo "Invalid input. Please enter a valid positive number.\n";
    exit;
}

// Get bet amount from user input
$betAmount = readline("Enter the bet amount per single spin: ");
if (!is_numeric($betAmount) || $betAmount <= 0) {
    echo "Invalid input. Please enter a valid positive number.\n";
    exit;
}

// Initial coins
$coins = $startCoins;

// Continuously play while there are enough coins
while ($coins >= $betAmount) {
    // Populate the board with random symbols
    $board = [];
    for ($i = 0; $i < $height; $i++) {
        $row = [];
        for ($j = 0; $j < $width; $j++) {
            $randomIndex = rand(0, count($symbols) - 1);
            $row[] = $symbols[$randomIndex];
        }
        $board[] = $row;
    }

    // Display board
    echo "Board:\n";
    foreach ($board as $row) {
        echo implode(", ", $row) . "\n";
    }
    echo "\n";

    // Define win multipliers for each symbol
    $winMultipliers = [
        'Q' => 5,
        'K' => 10,
        'J' => 15,
        'A' => 25
    ];

    // Define ANSI escape codes for colors
    $colorGreen = "\033[0;32m"; // Green color
    $colorReset = "\033[0m";    // Reset color

    // Calculate win amount
    $winAmount = 0;
    $horizontalMatches = 0;
    $verticalMatches = 0;
    foreach ($winMultipliers as $symbol => $multiplier) {
        $horizontalMatch = false;
        $verticalMatch = false;

        // Check horizontal lines
        for ($i = 0; $i < $height; $i++) {
            if (array_count_values($board[$i])[$symbol] === $width) {
                $horizontalMatch = true;
                break;
            }
        }

        // Check vertical lines
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

    // Calculate total win amount
    $winAmount = ($horizontalMatches + $verticalMatches) * $betAmount * array_sum($winMultipliers);

    if ($winAmount > 0) {
        echo $colorGreen . "Congratulations! You won!" . $colorReset . "\n";
    }

    // Update coins
    $coins += $winAmount - $betAmount;


    // Display result
    echo "Win Amount: $winAmount\n";
    echo "Coins Left: $coins\n\n";

    if ($coins < $betAmount) {
        echo "Game over! You ran out of coins.";
        break;
    } else {
        // Ask player to continue or quit
        $input = strtolower(readline("Continue? "));
        if (empty(trim($input))) {
            // Continue playing
        } else {
            echo "Bye! You have $coins coins left.\n";
            break; // Quit the game
        }
    }
}
