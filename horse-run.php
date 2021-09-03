<?php

$runLength = 15;

$horseCount = (int) readline('Enter the amount of horses participating: ');

$horses = [];

$game = [];

echo "Enter a character for each horse:" . PHP_EOL;

for ($i = 0; $i < $horseCount; $i++) {
    $horses[$i] = readline("Enter a character: ");
}

$coefficients = [
    1.1,
    1.2,
    1.3,
    1.4,
    1.5,
    2,
    1.01
];

$horseCoef = [];

foreach ($horses as $key => $horse) {
    $horseCoef[$key] = $coefficients[array_rand($coefficients)];
}

echo "Available betting options: " . PHP_EOL;
echo "Horse number | Horse symbol | Coefficient for winning" . PHP_EOL;

for ($i = 0; $i < $horseCount; $i++) {
    echo "{$i} | {$horses[$i]} | {$horseCoef[$i]}" . PHP_EOL;
}

$bets = [];

for ($i = 0; $i < $horseCount; $i++) {
    $bets[] = (int) readline("How much you want to bet on {$horses[$i]} ?");
}

for ($i = 0; $i < $horseCount; $i++) {
    for ($j = 0; $j < $runLength; $j++) {
        $game[$i][$j] = "_";
    }
    $game[$i][0] = $horses[$i];
}

function displayField(array &$game, int $playerCount, int $runLength): void {
    for ($i = 0; $i < $playerCount; $i++) {
        for ($j = 0; $j < $runLength; $j++) {
            echo $game[$i][$j] . " ";
        }
        echo PHP_EOL;
    }
}

displayField($game, $horseCount, $runLength);

$running = true;
$finished = 0;
$winners = [];

while ($running) {

    for ($i = 0; $i < $horseCount; $i++) {
        $currentPosition = array_search($horses[$i], $game[$i]);
        $temp = $game[$i][$currentPosition];
        $game[$i][$currentPosition] = "_";
        if ($currentPosition == $runLength - 2) {
            $game[$i][$currentPosition + 1] = $temp;
        } else {
            $game[$i][$currentPosition + rand(1, 2)] = $temp;
        }
    }

    displayField($game, $horseCount, $runLength);
    echo "*********************************" . PHP_EOL;

    for ($i = 0; $i < $horseCount; $i++) {
        if ($game[$i][$runLength - 1] === $horses[$i]) {
            $winners[] = $horses[$i];
            $finished++;
        }
    }

    sleep(2);
    if ($finished === $horseCount) $running = false;
}

echo "The race has ended" . PHP_EOL;

foreach ($winners as $key => $winner) {
    $key += 1;
    echo "{$key} place is {$winner}" . PHP_EOL;
}

$winnerIndex = array_search($winners[0], $horses);

$profit = number_format($horseCoef[$winnerIndex] * $bets[$winnerIndex], 0);

$total = (int) $profit - array_sum($bets);
echo "Your p/l in total = {$total} $";