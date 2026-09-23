<?php
$buffer = '<div></div><script src="other.js"></script><script src="tracking.js"></script>';
$wrapped_buffer = '<dummy>' . $buffer . '</dummy>';

$dom = new DOMDocument();
$encoded_buffer = mb_encode_numericentity( $wrapped_buffer, array( 0x80, 0x10FFFF, 0, 0x1FFFFF ), 'UTF-8' );
$dom->loadHTML( $encoded_buffer, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

$dummy = $dom->getElementsByTagName('dummy')->item(0);

echo "Child Nodes: " . $dummy->childNodes->length . "\n";
foreach ($dummy->childNodes as $node) {
    echo "Node: " . $node->nodeName . "\n";
    echo "Content: " . $dom->saveHTML($node) . "\n";
}
