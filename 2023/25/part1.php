<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$unique = [];
echo "graph {\n";
foreach ($lines as $line) {
    echo preg_replace('/(\w+): (.*)/', '  $1 -- {$2}', $line) . PHP_EOL;
}
echo "}\n";

// Solved via Graphviz in DOT format, once output is generated into file, e.g. graph.dot you can generate graph via:
//
// $ dot -Tsvg -Kneato graph.dot > graph.svg
//
// You will see 3 readable node connection between communities. Then manually remove those connections in DOT file
// and create new DOT file via ccomps command which would decompose graph and also print connected nodes via -v option:
//
// $ ccomps -v graph.dot > supgraphs.dot
//
// In the command output, you would see output like:
// (   0)     720 nodes    1611 edges
// (   1)     748 nodes    1666 edges
// Multiply nodes together, and you got the result.
