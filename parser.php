<?php

function convertToCsv($inputFile, $outputFile) {
    // Define the maps
    $maps = [
        'make' => 'Brand name',
        'model' => 'Model name',
        'colour' => 'Colour name',
        'capacity' => 'GB Spec name',
        'network' => 'Network name',
        'grade' => 'Grade Name',
        'condition' => 'Condition name'
    ];

    // Read the input file
    $inputData = [];
    $fileExtension = pathinfo($inputFile, PATHINFO_EXTENSION);

    switch ($fileExtension) {
        case 'csv':
            $inputData = readCsv($inputFile);
            break;
        case 'tsv':
            $inputData = readTsv($inputFile);
            break;
        case 'xml':
            $inputData = readXml($inputFile);
            break;
        case 'json':
            $inputData = readJson($inputFile);
            break;
        default:
            die("Unsupported file format");
    }

    // Write to CSV
    writeCsv($outputFile, $maps, $inputData);
}

function readCsv($file) {
    $data = [];
    $handle = fopen($file, 'r');
    if ($handle !== false) {
        while (($row = fgetcsv($handle)) !== false) {
            $data[] = $row;
        }
        fclose($handle);
    }
    return $data;
}

function readTsv($file) {
    $data = [];
    $handle = fopen($file, 'r');
    if ($handle !== false) {
        while (($row = fgetcsv($handle, 0, "\t")) !== false) {
            $data[] = $row;
        }
        fclose($handle);
    }
    return $data;
}

function readXml($file) {
    $data = [];
    $xml = simplexml_load_file($file);
    foreach ($xml->children() as $child) {
        $row = [];
        foreach ($child->children() as $key => $value) {
            $row[$key] = (string)$value;
        }
        $data[] = $row;
    }
    return $data;
}

function readJson($file) {
    $json = file_get_contents($file);
    $data = json_decode($json, true);
    return $data;
}

function writeCsv($file, $maps, $data) {
    $handle = fopen($file, 'w');
    if ($handle !== false) {
        // Write header
        fputcsv($handle, array_values($maps));

        // Write data
        foreach ($data as $row) {
            $mappedRow = [];
            foreach ($maps as $key => $label) {
                $mappedRow[] = isset($row[$key]) ? $row[$key] : '';
            }
            fputcsv($handle, $mappedRow);
        }

        fclose($handle);
    }
}

// CLI options
$options = getopt("", ["file:", "unique-combinations:"]);

// Check if required options are provided
if (!isset($options['file']) || !isset($options['unique-combinations'])) {
    die("Usage: php parser.php --file input.csv --unique-combinations output.csv" . PHP_EOL);
}

$inputFile = $options['file'];
$outputFile = $options['unique-combinations'];

// Example usage
convertToCsv($inputFile, $outputFile);

?>