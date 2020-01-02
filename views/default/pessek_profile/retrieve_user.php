<?php
/*
 * Author: Bryden Arndt
 * Date: January 26, 2015
 * Purpose: Provide search results to skills auto-suggest
 */

$user_guid = 36;//elgg_get_page_owner_guid();

$options = [ 'type' => 'user', 'relationship' => 'friend', 'relationship_guid' => $user_guid, 'inverse_relationship' => true ];
$testval = elgg_get_entities_from_relationship($options);

//$query = 'pes'; //htmlspecialchars($_GET['query']);

$query = htmlspecialchars($_GET['query']);
$result = array();

/*
foreach ($skills as $s) {
    if (strpos(strtolower($s), strtolower($query)) !== FALSE) {
        $result[] = array('value' => $s);
    }
}

*/


$result[] = array(
            'value' => 'puewe pessek hermand fulbert',
            'pos' => 2
        );
/*
$highest_relevance = array();
$high_relevance = array();
$med_relevance = array();
$low_relevance = array();
$lowest_relevance = array();

foreach ( $result as $r ) {
    if ( $r['pos'] == 0 ) {
        $highest_relevance[] = $r;
    }
    elseif ( $r['pos'] == 1 ) {
        $high_relevance[] = $r;
    }
    elseif ( $r['pos'] == 2 ) {
        $med_relevance[] = $r;
    }
    elseif ( $r['pos'] == 3 ) {
        $low_relevance[] = $r;
    }
    elseif ( $r['pos'] == 4 ) {
        $lowest_relevance[] = $r;
    }
}

$result = array_merge($highest_relevance, $high_relevance, $med_relevance, $low_relevance, $lowest_relevance);
*/
echo json_encode($result);

