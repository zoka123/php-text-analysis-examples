<?php

/**
 * An example of creating a creating document collection 
 * Document Collections allow you to work with a group of documents easily
 */

require_once('vendor/autoload.php');
//used to generate a chart from the output of PHP Text Analysis
require_once('utils/BarPageBuilder.php');


/**
 * @var string $book 
 */
$tomSawyerBook = file_get_contents('data/books/pg74.txt');
$huckFinnBook = file_get_contents('data/books/pg76.txt');

/**
 *  Create a tokenizer object to parse the book into a set of tokens
 *  
 */
$tokenizer = new \TextAnalysis\Tokenizers\GeneralTokenizer();

/**
 * Get the set of tokens generated by the tokenize and
 * create a token document from the tokens
 *  
 */
$tomSawyerDocument = new \TextAnalysis\Documents\TokensDocument($tokenizer->tokenize($tomSawyerBook));
$huckFinnDocument = new \TextAnalysis\Documents\TokensDocument($tokenizer->tokenize($huckFinnBook));

/**
 * create a document collection that can have filters or further analysis done
 */
$docCollection = new \TextAnalysis\Collections\DocumentArrayCollection(array($tomSawyerDocument, $huckFinnDocument));

/**
 *  Apply filters to the document collection
 *  lower case the documents, remove quotes and remove stop words
 */

$filters = array(
    new \TextAnalysis\Filters\LowerCaseFilter(),
    new \TextAnalysis\Filters\QuotesFilter(),
    new \TextAnalysis\Filters\EnglishStopWordsFilter()
);
        
/**
 * Applies the filters to all the documents 
 */        
$docCollection->applyTransformations($filters);        

/**
 *  See how the top 10 keyword frequency has changed by applying the filters compared to example 01
 */

$freqDist = new \TextAnalysis\Analysis\FreqDist($docCollection[0]->getDocumentData());

/**
 * Get the top 10 most used words in Tom Sawyer 
 */
$top10 = array_splice($freqDist->getKeyValuesByFrequency(), 0, 10);


/** 
 * Use High Charts to visualize the data
 */
$pageBuilder = new BarPageBuilder($top10);

$html = $pageBuilder->getHtmlPage();

file_put_contents("pub/pages/example_02_document_collections.html", $html);

echo 'go to the directory pub/pages/example_02_document_collections.html and open the file with your web browser'.PHP_EOL;
/**
 *  go to the directory in this project and open the file with your web browser
 */






